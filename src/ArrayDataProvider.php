<?php
/**
 * Created by PhpStorm.
 * User: minhao
 * Date: 2015-09-15
 * Time: 11:45
 */
namespace Oasis\Mlib\Utils;

class ArrayDataProvider extends AbstractDataProvider
{
    protected $data              = [];
    protected $cascade_delimiter = null;

    function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @inheritdoc
     */
    protected function getValue($key)
    {
        if (!array_key_exists($key, $this->data)) {
            $value = null;

            if ($this->cascade_delimiter) {
                $parts = explode($this->cascade_delimiter, $key);
                for ($i = 0; $i < count($parts) - 1; ++$i) {
                    $branch = implode($this->cascade_delimiter, array_slice($parts, 0, $i + 1));
                    $leaf   = implode($this->cascade_delimiter, array_slice($parts, $i + 1));
                    if (is_array($leafNode = $this->getValue($branch))) {
                        $leafDp = new ArrayDataProvider($leafNode);
                        $value  = $leafDp->getValue($leaf);
                    }
                    if ($value !== null) {
                        break;
                    }
                }
            }

            return $value;
        }

        return $this->data[$key];
    }

    /**
     * @return string
     */
    public function getCascadeDelimiter()
    {
        return $this->cascade_delimiter;
    }

    /**
     * @param string $cascade_delimiter
     */
    public function setCascadeDelimiter($cascade_delimiter)
    {
        $this->cascade_delimiter = $cascade_delimiter;
    }
}
