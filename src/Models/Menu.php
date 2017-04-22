<?php
/**
 * Menu
 */
namespace Delatbabel\NestedMenus\Models;

use Baum\Node;
use Cviebrock\EloquentSluggable\Sluggable;

/**
 * Menu
 *
 * A Laravel 5 package for adding a menu hierarchy to a website.
 *
 * @link https://github.com/delatbabel/nestedcategories
 */
class Menu extends Node
{
    use Sluggable;

    /**
     * Stores the old parent id before editing
     * @var integer
     */
    protected $oldParentId = null;

    protected $casts = [
        'extended_data'     => 'array',
    ];

    /**
     * Sluggable configuration.
     *
     * Used for Cviebrock/EloquentSluggable
     *
     * @var array
     */
    public function sluggable() {
        return [
            'slug' => [
                'source'         => 'name',
                'unique'         => true,
                'includeTrashed' => true,
            ]
        ];
    }

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::updating(function ($category) {

            // Baum triggers a parent move, which puts the item last in the list,
            // even if the old and new parents are the same
            if ($category->isParentIdSame()) {
                $category->stopBaumParentMove();
            }

        });
    }

    /**
     * Check for dirty parent ID.
     *
     * Returns true if the parent_id value in the database is different to the current
     * value in the model's dirty attributes
     *
     * @return bool
     */
    protected function isParentIdSame()
    {
        $dirty = $this->getDirty();
        $oldNavItem = self::where('id', '=', $this->id)->first();
        $oldParent = $oldNavItem->parent;
        $oldParentId = $oldParent->id;
        $isParentColumnSet = isset($dirty[$this->getParentColumnName()]);
        if ($isParentColumnSet) {
            $isNewParentSameAsOld = $dirty[$this->getParentColumnName()] == $oldParentId;
        } else {
            $isNewParentSameAsOld = false;
        }
        return $isParentColumnSet && $isNewParentSameAsOld;
    }

    /**
	 * Reset parent ID.
	 *
	 * Removes the parent_id field from the model's attributes and sets $moveToNewParentId
	 * static property on the parent Baum\Node model class to false to prevent Baum from
	 * triggering a move. This can be required because Baum triggers a parent move, which
	 * puts the item last in the list, even if the old and new parents are the same.
	 *
	 * @return void
     */
    protected function stopBaumParentMove()
    {
        unset($this->{$this->getParentColumnName()});
        static::$moveToNewParentId = false;
    }

    /**
     * Get path attribute.
     *
     * Accessor for path attribute, which is a string consisting of the ancestors
     * of each node, separated by " > ".
     *
     * @return string
     */
    public function getPathAttribute()
    {
        $ancestors = $this->getAncestors();
        $return = array();
        foreach ($ancestors as $ancestor) {
            $return[] = $ancestor->name;
        }
        $return[] = $this->name;
        return implode(' > ', $return);
    }
}
