<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class CandidateImportController extends Controller
{
    public function index(string $tenant)
    {
        return view('tenant.candidates.import', compact('tenant'));
    }

    public function store(Request $request, string $tenant)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,xls|max:10240', // 10MB max
            'source' => 'nullable|string|max:255',
        ]);

        try {
            $import = new CandidateImport(tenant_id(), $request->input('source', 'Import'));
            
            Excel::import($import, $request->file('file'));

            $importedCount = $import->getRowCount();
            $errors = $import->getErrors();

            if ($importedCount > 0) {
                return redirect()
                    ->route('tenant.candidates.index', $tenant)
                    ->with('success', "Successfully imported {$importedCount} candidates.");
            } else {
                return redirect()
                    ->back()
                    ->with('error', 'No candidates were imported. Please check your file format and try again.');
            }

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Import failed: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="candidates_import_template.csv"',
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fwrite($file, "\xEF\xBB\xBF");
            
            // Headers
            fputcsv($file, [
                'first_name',
                'last_name', 
                'primary_email',
                'primary_phone',
                'source',
                'tags'
            ]);
            
            // Sample data
            fputcsv($file, [
                'John',
                'Doe',
                'john.doe@example.com',
                '+1234567890',
                'LinkedIn',
                'PHP,Senior,Remote'
            ]);
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}

class CandidateImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, SkipsOnFailure
{
    use Importable, SkipsErrors, SkipsFailures;

    private $tenantId;
    private $source;
    private $rowCount = 0;

    public function __construct($tenantId, $source = 'Import')
    {
        $this->tenantId = $tenantId;
        $this->source = $source;
    }

    public function model(array $row)
    {
        $this->rowCount++;

        // Skip if email is empty
        if (empty($row['primary_email'])) {
            return null;
        }

        // Check if candidate already exists
        $existingCandidate = Candidate::where('tenant_id', $this->tenantId)
            ->where('primary_email', $row['primary_email'])
            ->first();

        if ($existingCandidate) {
            // Update existing candidate
            $existingCandidate->update([
                'first_name' => $row['first_name'] ?? $existingCandidate->first_name,
                'last_name' => $row['last_name'] ?? $existingCandidate->last_name,
                'primary_phone' => $row['primary_phone'] ?? $existingCandidate->primary_phone,
                'source' => $row['source'] ?? $existingCandidate->source,
            ]);

            // Handle tags
            if (!empty($row['tags'])) {
                $this->attachTags($existingCandidate, $row['tags']);
            }

            return null; // Don't create new model
        }

        // Create new candidate
        $candidate = new Candidate([
            'tenant_id' => $this->tenantId,
            'first_name' => $row['first_name'] ?? '',
            'last_name' => $row['last_name'] ?? '',
            'primary_email' => $row['primary_email'],
            'primary_phone' => $row['primary_phone'] ?? null,
            'source' => $row['source'] ?? $this->source,
        ]);

        $candidate->save();

        // Handle tags
        if (!empty($row['tags'])) {
            $this->attachTags($candidate, $row['tags']);
        }

        return $candidate;
    }

    public function rules(): array
    {
        return [
            'primary_email' => 'required|email',
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'primary_phone' => 'nullable|string|max:255',
            'source' => 'nullable|string|max:255',
            'tags' => 'nullable|string|max:1000',
        ];
    }

    private function attachTags($candidate, $tagsString)
    {
        if (empty($tagsString)) {
            return;
        }

        $tagNames = array_map('trim', explode(',', $tagsString));
        
        foreach ($tagNames as $tagName) {
            if (empty($tagName)) {
                continue;
            }

            $tag = Tag::firstOrCreate(
                [
                    'tenant_id' => $this->tenantId,
                    'name' => $tagName
                ],
                [
                    'tenant_id' => $this->tenantId,
                    'name' => $tagName,
                    'color' => $this->generateRandomColor()
                ]
            );

            $candidate->tags()->syncWithoutDetaching([$tag->id]);
        }
    }

    private function generateRandomColor()
    {
        $colors = [
            '#3B82F6', '#EF4444', '#10B981', '#F59E0B', '#8B5CF6',
            '#06B6D4', '#84CC16', '#F97316', '#EC4899', '#6366F1'
        ];
        
        return $colors[array_rand($colors)];
    }

    public function getRowCount()
    {
        return $this->rowCount;
    }
}
