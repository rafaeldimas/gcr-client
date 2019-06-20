<?php


namespace Gcr\Traits;

use Illuminate\Database\Eloquent\Model;

trait AccessLinksController
{
    /**
     * @param Model|null $model
     * @return string
     */
    public function linkEdit(Model $model = null)
    {
        return route("{$this->getBaseRouteModel($model)}.edit", $model ?: $this);
    }

    /**
     * @param Model|null $model
     * @return string
     */
    public function linkDestroy(Model $model = null)
    {
        return route("{$this->getBaseRouteModel($model)}.destroy", $model ?: $this);
    }

    /**
     * @param Model|null $model
     * @return string
     */
    public function getBaseRouteModel($model)
    {
        $classBaseName = mb_strtolower(class_basename($model ?: $this));
        return "dashboard.{$classBaseName}";
    }
}
