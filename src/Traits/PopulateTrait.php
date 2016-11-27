<?php

namespace App\Traits;

trait PopulateTrait
{

    public function populateFromJsonObject(array $fields)
    {
        foreach ($fields as $field => $value) {

            $setter = sprintf("set%s", str_replace(" ", "", ucwords(str_replace("_", " ", $field))));

            if (is_callable(array($this, $setter)) === false) {
                throw new \Exception(sprintf("*%s* is not allowed in %s", $field, get_class($this)), 401);
            }

            if (gettype($value) == 'string') {
                $value = chop(trim($value));
            }

            call_user_func(array($this, $setter), $value);

        }
    }
}