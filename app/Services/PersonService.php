<?php

namespace App\Services;

use App\Models\Person;
use App\Models\PersonAka;

class PersonService extends AbstractService
{

    public const STRONG = 'strong';
    public const WEAK = 'weak';

    /**
     * Get names
     *
     * @param  array  $data
     * @return array
     */
    public function getNames(array $data): array
    {
        $type = strtolower($data['type'] ?? '');
        $names = explode(' ', trim($data['name']));

        $result = array_merge(
            $this->getPersonNames($names),
            $this->getPersonAkaNames($names, $type)
        );

        return array_unique($result, SORT_REGULAR);
    }

    /**
     * Get person names
     *
     * @param  array  $names
     * @return array
     */
    private function getPersonNames(array $names): array
    {
        $result = [];

        foreach ($names as $name) {
            $result = array_merge(
                $result,
                Person::select('uid', 'first_name', 'last_name')
                    ->where('first_name', 'like', $name)
                    ->orWhere('last_name', 'like', $name)
                    ->get()
                    ->toArray()
            );
        }

        return $result;
    }

    /**
     * Get person aka names
     *
     * @param  array  $names
     * @param  string  $type
     * @return array
     */
    private function getPersonAkaNames(array $names, string $type): array
    {
        $result = [];

        foreach ($names as $name) {
            $person_aka = PersonAka::select('uid', 'first_name', 'last_name')
                ->where('first_name', 'like', $name)
                ->orWhere('last_name', 'like', $name);

            if ($type === self::STRONG || $type === self::WEAK) {
                $person_aka->where('category', $type);
            }

            $result = array_merge(
                $result,
                $person_aka->get()->toArray()
            );
        }

        return $result;
    }
}
