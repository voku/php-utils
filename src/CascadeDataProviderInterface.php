<?php
/**
 * Created by PhpStorm.
 * User: minhao
 * Date: 2016-02-17
 * Time: 21:03
 */

namespace Oasis\Mlib\Utils;

interface CascadeDataProviderInterface extends DataProviderInterface
{
    public function getCascadeDelimiter();

    public function setCascadeDelimiter($cascade_delimiter);
}
