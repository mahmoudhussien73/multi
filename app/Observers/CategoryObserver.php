<?php

namespace App\Observers;

use App\Models\Admin\Category;

class CategoryObserver
{
    /**
     * Handle the Category "created" event.
     *
     * @param  \App\Models\Admin\Category  $category
     * @return void
     */
    public function created(Category $category)
    {
        //
    }

    /**
     * Handle the Category "updated" event.
     *
     * @param  \App\Models\Admin\Category  $category
     * @return void
     */
    public function updated(Category $category)
    {
        $category->childrens()->update(['status' => $category->status]);
    }

    /**
     * Handle the Category "deleted" event.
     *
     * @param  \App\Models\Admin\Category  $category
     * @return void
     */
    public function deleted(Category $category)
    {
        //
    }

    /**
     * Handle the Category "restored" event.
     *
     * @param  \App\Models\Admin\Category  $category
     * @return void
     */
    public function restored(Category $category)
    {
        //
    }

    /**
     * Handle the Category "force deleted" event.
     *
     * @param  \App\Models\Admin\Category  $category
     * @return void
     */
    public function forceDeleted(Category $category)
    {
        //
    }
}
