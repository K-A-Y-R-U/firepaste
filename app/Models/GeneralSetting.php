<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeneralSetting extends Model
{
    protected $table = 'general_settings';
    protected $fillable = ['site_name', 'site_description', 'site_logo', 'site_favicon', 'theme_color', 'support_email', 'support_phone', 'google_analytics_id', 'posthog_html_snippet', 'seo_title', 'seo_keywords', 'seo_metadata', 'email_settings', 'email_from_address', 'email_from_name', 'social_networks', 'more_configs'];
}