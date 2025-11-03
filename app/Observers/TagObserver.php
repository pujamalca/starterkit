<?php

namespace App\Observers;

use App\Models\Tag;

class TagObserver
{
    public function created(Tag $tag): void
    {
        activity('tag')
            ->performedOn($tag)
            ->withProperties($tag->getChanges())
            ->log('created');
    }

    public function updated(Tag $tag): void
    {
        activity('tag')
            ->performedOn($tag)
            ->withProperties($tag->getChanges())
            ->log('updated');
    }

    public function deleted(Tag $tag): void
    {
        activity('tag')
            ->performedOn($tag)
            ->log('deleted');
    }
}
