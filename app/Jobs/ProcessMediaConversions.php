<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\MediaLibrary\Conversions\ConversionCollection;
use Spatie\MediaLibrary\Conversions\FileManipulator;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ProcessMediaConversions implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public $deleteWhenMissingModels = true;

    public function __construct(
        protected Media $media,
        protected bool $onlyMissing = false,
    ) {
        $connection = config('media-library.queue_connection_name');
        $queue = config('media-library.queue_name')
            ?: config('queue.connections.'.config('queue.default').'.queue', 'default');

        if ($connection) {
            $this->onConnection($connection);
        }

        if ($queue) {
            $this->onQueue($queue);
        }
    }

    public function handle(FileManipulator $fileManipulator): void
    {
        $conversions = ConversionCollection::createForMedia($this->media);

        $fileManipulator->performConversions(
            $conversions,
            $this->media,
            $this->onlyMissing
        );
    }
}

