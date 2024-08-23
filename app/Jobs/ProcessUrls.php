<?php

namespace App\Jobs;

use App\Models\Domain;
use App\Models\Url;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessUrls implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $urls;

    /**
     * Create a new job instance.
     */
    public function __construct(array $urls)
    {
        $this->urls = $urls;
    }

    protected function getBaseDomain($url) {
        $parsedUrl = parse_url($url);
        return $parsedUrl['host'] ?? null;
    }

    /**
     * Execute the job.
     */
    public function handle(): array
    {
        $domainsToInsert = [];
        $urlsToInsert = [];
        $duplicateUrls = [];

        foreach ($this->urls as $url) {
            $url = trim($url);
            $baseDomain = $this->getBaseDomain($url);

            if ($baseDomain) {
                $domainsToInsert[$baseDomain] = [
                    'domain_name' => $baseDomain,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Insert domains in bulk
        Domain::insertOrIgnore($domainsToInsert);

        $existingDomains = Domain::whereIn('domain_name', array_keys($domainsToInsert))->get()->keyBy('domain_name');

        // Fetch all existing URLs related to these domains in one query
        $existingUrls = Url::whereIn('domain_id', $existingDomains->pluck('id'))
            ->pluck('domain_id', 'full_url');

        foreach ($this->urls as $url) {
            $url = trim($url);
            $baseDomain = $this->getBaseDomain($url);
            if ($baseDomain && isset($existingDomains[$baseDomain])) {
                $domainId = $existingDomains[$baseDomain]->id;

                if ($existingUrls->has($url)) {
                    // Track duplicate URLs
                    $duplicateUrls[] = $url;
                } else {
                    $urlsToInsert[] = [
                        'domain_id' => $domainId,
                        'full_url' => $url,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                    // Add to the existing URLs list to prevent duplicate processing
                    $existingUrls[$domainId] = $url;
                }
            }
        }

        // Insert URLs in bulk
        Url::insertOrIgnore($urlsToInsert);

        $insertedCount = count($urlsToInsert);
        $duplicateCount = count($duplicateUrls);

        return [
            "insertedCount" => $insertedCount,
            "duplicateCount" => $duplicateCount
        ];
    }
}
