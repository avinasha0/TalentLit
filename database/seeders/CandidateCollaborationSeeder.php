<?php

namespace Database\Seeders;

use App\Models\Candidate;
use App\Models\CandidateNote;
use App\Models\Tag;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CandidateCollaborationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first tenant and user for demo data
        $tenant = Tenant::first();
        $user = User::first();
        
        if (!$tenant || !$user) {
            $this->command->info('No tenant or user found. Please run the main seeder first.');
            return;
        }

        // Create some demo tags
        $tags = [
            'High Priority',
            'Technical',
            'Remote',
            'Senior Level',
            'Entry Level',
            'Fast Track',
            'Referral',
            'Internal',
        ];

        $createdTags = collect();
        foreach ($tags as $tagName) {
            $tag = Tag::firstOrCreate(
                [
                    'tenant_id' => $tenant->id,
                    'name' => $tagName,
                ]
            );
            $createdTags->push($tag);
        }

        // Get some candidates to add notes and tags to
        $candidates = Candidate::where('tenant_id', $tenant->id)->take(3)->get();

        foreach ($candidates as $candidate) {
            // Add some random tags to each candidate
            $randomTags = $createdTags->random(rand(1, 3));
            foreach ($randomTags as $tag) {
                $candidate->tags()->attach($tag->id, ['tenant_id' => $tenant->id]);
            }

            // Add some sample notes
            $sampleNotes = [
                'Great communication skills during initial screening.',
                'Has relevant experience in the required technologies.',
                'Follow up needed - waiting for references.',
                'Strong cultural fit with our team values.',
                'Requires additional technical assessment.',
            ];

            foreach (array_slice($sampleNotes, 0, rand(1, 3)) as $noteBody) {
                CandidateNote::create([
                    'tenant_id' => $tenant->id,
                    'candidate_id' => $candidate->id,
                    'user_id' => $user->id,
                    'body' => $noteBody,
                ]);
            }
        }

        $this->command->info('Created ' . count($createdTags) . ' tags and added sample notes and tags to ' . $candidates->count() . ' candidates.');
    }
}
