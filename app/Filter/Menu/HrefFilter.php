<?php


namespace Gcr\Filter\Menu;


use JeroenNoten\LaravelAdminLte\Menu\Filters\HrefFilter as HrefFilterAdminLte;

class HrefFilter extends HrefFilterAdminLte
{
    protected function makeHref($item)
    {
        if (isset($item['url'])) {
            return $this->urlGenerator->to($item['url']);
        }

        if (isset($item['route'])) {
            if (is_array($item['route'])) {
                $params = array_get($item['route'], 'params', []);
                return $this->urlGenerator->route($item['route']['name'], $params);
            } else {
                return $this->urlGenerator->route($item['route']);
            }
        }

        return '#';
    }
}
