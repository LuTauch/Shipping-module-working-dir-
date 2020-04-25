<?php


namespace LuTauch\App\Model\PickupPoint;


use Tracy\Debugger;

class ZasilkovnaPickUpPointDownload
{
    /**
     * @var string
     */
    private $apiKey;
    /**
     * @var string
     */
    private $apiUrl;

    const BRANCH_FILE = 'branch.json';

    public function __construct(string $apiKey, string $apiUrl) {

        $this->apiKey = $apiKey;
        $this->apiUrl = $apiUrl;
    }


    /* Vraci pobocky Zasilkovny
    * @return bool|object Pole s pobockami nebo false
    */
    public function getBranches()
    {
        try
        {
            //nacte json
            $json = file_get_contents("{$this->apiUrl}{$this->apiKey}/" . self::BRANCH_FILE);
            //vytvori object tridy stdclass
            $branches = json_decode($json);
            return $branches->data;
        }
        catch (\Exception $e)
        {
            Debugger::log('Chyba pri zpracovani odpovedi s pobockami Zasilkovny: ' . $e->getMessage(),
                'Zasilkovna.getBranches');
            return false;
        }
    }

}