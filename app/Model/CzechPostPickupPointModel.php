<?php


namespace LuTauch\App\Model;


class CzechPostPickupPointModel extends BaseModel
{
    const ZIP = 'psc';
    const TITLE = 'nazev';
    const REGION = 'okres';
    const ADDRESS = 'adresa';

    /**
     * @inheritDoc
     */
    public function getTableName()
    {
       return 'ceska_posta';
    }



}