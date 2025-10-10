<?php

namespace App\Models;

use App\Models\Concerns\TenantScoped;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TenantBranding extends Model
{
    use HasFactory, HasUuids, TenantScoped;

    protected $fillable = [
        'tenant_id',
        'logo_path',
        'hero_image_path',
        'primary_color',
        'intro_headline',
        'intro_subtitle',
        'company_description',
        'benefits_text',
        'contact_email',
        'contact_phone',
        'linkedin_url',
        'twitter_url',
        'facebook_url',
        'show_benefits',
        'show_company_info',
        'show_social_links',
    ];

    protected $table = 'tenant_branding';
    protected $keyType = 'string';
    public $incrementing = false;

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}