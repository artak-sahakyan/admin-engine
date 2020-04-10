<?php
namespace common\helpers;

use yii\helpers\Url;

class CategoryHelper
{
    /**
     * getCatogiriesForDropDownm
     */
    public static function getCatogiriesForDropDownm($allCategories, $childTitlePrefix = null)
    {
        $tree = self::getCatogiriesWithChilds($allCategories);
        $forDropDown = [];

        foreach ($tree as $index => $category) {
            foreach ($category as $attrs => $value) {
                if($attrs != 'childs') {
                    $forDropDown[$index][$attrs] = $value;
                } else {
                     self::setChilds($value, $childTitlePrefix, $forDropDown);
                }
            }
        }

        return $forDropDown;
    }

    private static function setChilds($value, $childTitlePrefix, &$forDropDown) {
        $i = 1;
        foreach ($value as $childKey => $child) {
            if($childTitlePrefix) {
                $child['title'] =  ' '. $childTitlePrefix .' ' . $child['title'];
            }

            $forDropDown[$childKey] = $child;

            if(isset($child['childs'])) {
                 self::setChilds($child['childs'], str_repeat($childTitlePrefix, $i++), $forDropDown);
            }
        }
    }

    /**
     * getCatogiriesWithChildIds
     */
    public static function getCatogiriesWithChildIds($categories, $mainTitle=null)
    {
        $categories = self::getCatogiriesWithChilds($categories);
        $data = [];

        foreach ($categories as $category) {
            $ids = [];
            $mainTitle = '';
            if(!$category['parent_id']) {
                $ids[] = $category['id'];
                $mainTitle = $category['title'];
                if(!empty($category['childs'])) {
                    foreach ($category['childs'] as $child) {
                        $ids[] = $child['id'];
                    }
                }
            }

            if(!$mainTitle) {
                $data[$ids[0]] = $ids;
            } else {
                $data[$ids[0]] = [
                    'ids' => $ids,
                    'title' => $mainTitle
                ];
            }
        }
        return $data;
    }

    /**
     * @param array $allCategories
     * @return array
     */
    public static function getCatogiriesWithChilds($allCategories)
    {
        $categoriesMap = [];

        foreach ($allCategories as $category) {
            $categoriesMap[$category['id']] = $category;
        }

        return  self::getCategoriesTree($categoriesMap);
    }

    /**
     * @param array $categoriesMap
     * @return array
     */
    protected static function getCategoriesTree($categoriesMap)
    {
        foreach ($categoriesMap as $categoryId => &$category) {
            if($category['parent_id'] && !empty($categoriesMap[$category['parent_id']])) {
                $categoryParent = &$categoriesMap[$category['parent_id']];
                if(!isset($categoryParent['childs'])) {
                    $categoryParent['childs'] = [];
                }
                $categoryParent['childs'][$categoryId] = &$category;
            }
        }

        foreach ($categoriesMap as $categoryId => $categoryNew) {
            if($categoryNew['parent_id']) {
                unset($categoriesMap[$categoryId]);
            }
        }

        return $categoriesMap;
    }


    /**
     * @param object $category
     * @return array
     */
    public static function getCategoryCrumbs(\common\models\ArticleCategory $category) {
        $categories[] = $category;

        $categories = self::getAllParents($category, $categories);
        $categories = array_reverse($categories);

        $url = '';
        $breadcrumbs = [];

        foreach ($categories as $key => $value) {
            $url .= '/' . $value->slug;
            $breadcrumbs[] = ['label' => $value->title, 'url'=> Url::to($url)];
        }

        return ($breadcrumbs);
    }

    private static function getAllParents(\common\models\ArticleCategory $category, $categories = null)
    {
        if(isset($category->parent)) {
            $categories[] = $category->parent;
            $categories = self::getAllParents($category->parent, $categories);
        }

        return $categories;
    }

}
