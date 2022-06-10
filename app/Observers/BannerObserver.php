<?php

namespace App\Observers;

use App\Models\Admin\Banner;

class BannerObserver
{
    /**
     * Handle the Banner "created" event.
     *
     * @param  \App\Models\Admin\Banner  $banner
     * @return void
     */
    public function created(Banner $banner)
    {
        //
    }

    /**
     * Handle the Banner "updated" event.
     *
     * @param  \App\Models\Admin\Banner  $banner
     * @return void
     */
    public function updated(Banner $banner)
    {
        $banner->childrens()->update(['status' => $banner->status]);
    }

    /**
     * Handle the Banner "deleted" event.
     *
     * @param  \App\Models\Admin\Banner  $banner
     * @return void
     */
    public function deleted(Banner $banner)
    {
        //
    }

    /**
     * Handle the Banner "restored" event.
     *
     * @param  \App\Models\Admin\Banner  $banner
     * @return void
     */
    public function restored(Banner $banner)
    {
        //
    }

    /**
     * Handle the Banner "force deleted" event.
     *
     * @param  \App\Models\Admin\Banner  $banner
     * @return void
     */
    public function forceDeleted(Banner $banner)
    {
        //
    }
}
