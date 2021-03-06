<?php

class LinkwiseFeed extends Salvaon {

    /**
     * The file associated with the model
     *
     * @var string
     */
    public $file = '';

    /**
     * Root element of the document
     *
     * @var string
     */
    public $root = 'product_feed';

    /**
     * Child elements of the root
     *
     * @var string
     */
    public $child = 'program/product';

    /**
     * The primary key for the model
     *
     * @var string
     */
    public $primaryKey = 'lw_product_id';










    /**
     * SimpleXMLElement object from loaded XML file.
     *
     * @var object|array|null
     */
    public $xml = null;

    /**
     * Attributes to add new child.
     *
     * @var array
     */
    protected $new = array();

    /**
     * SimpleXMLElement xpath expression in parts.
     *
     * @var array
     */
    protected $query = array(
        'where'    => '',
        'contains' => '',
        'order'    => false,
        'limit'    => false
    );

    /**
     * Full path to XML file.
     *
     * @var string
     */
    protected static $path;

    /**
     * The array of booted models.
     *
     * @var array
     */
    protected static $booted = array();

    /**
     * SimpleXMLElement object from loaded XML file.
     *
     * @var object
     */
    protected static $xmlObject = null;

    /**
     * Create a new Salvaon model instance.
     *
     * @return Salvaon
     */
    public function __construct($file) {
        $this->file = $file;
        $this->bootIfNotBooted();
    }

    /**
     * Get an iterator for the XML object.
     *
     * @return \ArrayIterator
     */
    public function getIterator() {
        return new ArrayIterator($this->xml);
    }

    /**
     * Check if the model needs to be booted and if so, do it.
     *
     * @return void
     */
    protected function bootIfNotBooted() {
        $class = get_class($this);

        if (!isset(static::$booted[$class])) {
            static::$path = Config::get('salvaon.path', storage_path() . '/xml') . '/' . $this->file;
            static::$xmlObject = new SimpleXMLElement(static::$path, 0, true);

            static::$booted[$class] = array(
                'path' => static::$path,
//                'object' => new SimpleXMLElement(static::$path, 0, true)
                'object' => simplexml_load_file(static::$path, "SimpleXMLElement", LIBXML_PARSEHUGE)
            );
        } else {
            static::$path = static::$booted[$class]['path'];
            static::$xmlObject = static::$booted[$class]['object'];
        }
    }

    /**
     * SimpleXML xpath method.
     *
     * @param string $query
     * @return array
     */
    protected function xpath($query) {
        return static::$xmlObject->xpath($query);
    }

    /**
     * Find a model by its primary key or fail if not exists.
     *
     * @param mixed $id
     * @return array|static
     */
    public static function findOrFail($id) {
        $model = self::find($id);

        if (is_null($model)) {
            throw (new ModelNotFoundException)->setModel(get_called_class());
        }

        return $model;
    }

    /**
     * Find a model by its primary key.
     *
     * @param mixed $id
     * @return array|static
     */
    public function doFind($id) {
        $query = "/{$this->root}/{$this->child}[{$this->primaryKey}=\"{$id}\"]";

        $result = $this->xpath($query);

        if (empty($result)) {
            return null;
        }

        $this->xml = $result[0];

        return $this;
    }

    /**
     * Get all items from model.
     *
     * @return array|static
     */
    public function doAll() {
        $query = "/{$this->root}/{$this->child}";

        $this->xml = $this->xpath($query);

        return $this;
    }

    /**
     * Save changes from XML object.
     *
     * @param array $attributes Attributes for child that you are added.
     * @return Salvaon
     * @throws Exception
     */
    public function save($attributes = array()) {
        if (!empty($this->new)) {
            $this->addChildWithAttributes($attributes);
        }

        $result = static::$xmlObject->asXML(static::$path);

        if ($result === false) {
            throw new Exception('Cannot save values into "' . static::$path . '".');
        }

        return $this;
    }

    /**
     * Delete XML node from object.
     *
     * @return Salvaon
     */
    public function delete() {
        unset($this->xml[0]);

        return $this->save();
    }

    /**
     * Count XML nodes from object.
     *
     * @return integer
     */
    public function count() {
        return count($this->xml);
    }

    /**
     * Select something from XML file with xpath syntax.
     *
     * @param string $query
     * @return array|static
     */
    public function doRaw($query) {
        $this->xml = $this->xpath($query);
        return $this;
    }

    /**
     * Initialize query builder static instance.
     *
     * @return static
     */
    public function doSelect() {
        return $this;
    }

    /**
     * where()
     *
     * @param string $attribute
     * @param string $operator
     * @param mixed $value
     * @param string $where [default: 'and']
     * @return Salvaon
     */
    public function where($attribute, $operator, $value, $where = 'and') {
        if (!empty($this->query['where'])) {
            $this->query['where'] .= " {$where} ";
        }

        $this->query['where'] .= "{$attribute}{$operator}\"{$value}\"";

        return $this;
    }

    /**
     * orWhere()
     *
     * @param string $attribute
     * @param string $operator
     * @param mixed $value
     * @return Salvaon
     */
    public function orWhere($attribute, $operator, $value) {
        return $this->where($attribute, $operator, $value, 'or');
    }

    /**
     * contains()
     *
     * @param string $attribute
     * @param mixed $value
     * @param string $contains [default: 'and']
     * @return Salvaon
     */
    public function contains($attribute, $value, $contains = 'and') {
        if (!empty($this->query['contains'])) {
            $this->query['contains'] .= " {$contains} ";
        }

        $this->query['contains'] .= "contains({$attribute}, \"{$value}\")";

        return $this;
    }

    /**
     * orContains()
     *
     * @param string $attribute
     * @param mixed $value
     * @return Salvaon
     */
    public function orContains($attribute, $value) {
        return $this->contains($attribute, $value, 'or');
    }

    /**
     * skip()
     *
     * @param integer $skip
     * @return Salvaon
     */
    public function skip($skip) {
        $this->query['limit']['skip'] = $skip;

        return $this;
    }

    /**
     * take()
     *
     * @param integer $take
     * @return Salvaon
     */
    public function take($take) {
        $this->query['limit']['take'] = $take;

        return $this;
    }

    /**
     * orderBy()
     *
     * @param string $attribute
     * @param string $type
     * @return Salvaon
     */
    public function orderBy($attribute, $type = "asc") {
        $this->query['order']['attribute'] = $attribute;
        $this->query['order']['type'] = $type;

        return $this;
    }

    /**
     * get()
     *
     * @return array
     */
    public function get() {
        $query = "/{$this->root}/{$this->child}";

        if (!empty($this->query['where'])) {
            $query.= "[{$this->query['where']}]";
        }

        if (!empty($this->query['contains'])) {
            $query.= "[{$this->query['contains']}]";
        }

        $result = $this->xpath($query);

        if (is_array($this->query['order'])) {
            $result = $this->queryOrderBy($result, $this->query['order']['type']);
        }

        if (is_array($this->query['limit'])) {
            $skip = isset($this->query['limit']['skip']) ? $this->query['limit']['skip'] : 0;
            $result = $this->queryLimit($result, $skip, $this->query['limit']['take']);
        }

        $this->xml = $result;

        return $this;
    }

    /**
     * queryLimit()
     *
     * @param array $array
     * @param integer $skip
     * @param integer $take
     * @return array
     */
    protected function queryLimit($array, $skip, $take) {
        return array_slice($array, $skip, $take);
    }

    /**
     * queryOrderBy()
     *
     * @param array $array
     * @param string $type
     * @return array
     */
    protected function queryOrderBy($array, $type) {
        usort($array, __class__ . '::queryCompare');

        return ($type == 'desc') ? array_reverse($array) : $array;
    }

    /**
     * queryCompare()
     *
     * @param string $a
     * @param string $b
     * @return integer
     */
    protected function queryCompare($a, $b) {
        return strnatcmp($a->{$this->query['order']['attribute']}, $b->{$this->query['order']['attribute']});
    }

    /**
     * Add child with attributes to $xmlObject.
     *
     * @param array $attributes
     * @return void
     */
    protected function addChildWithAttributes($attributes = array()) {
        $child = static::$xmlObject->addChild($this->child);

        foreach ($attributes as $key => $value) {
            $child->addAttribute($key, $value);
        }

        foreach ($this->new as $key => $value) {
            $child->addChild($key, $value);
        }
    }

    /**
     * Get array of SimpleXMLElement objects
     *
     * @return array|null
     */
    public function toArray() {
        if (!is_array($this->xml) && !is_null($this->xml)) {
            return array($this->xml);
        }

        return $this->xml;
    }

    /**
     * Check if array of SimpleXMLElement objects is empty
     *
     * @return boolean
     */
    public function isEmpty() {
        if (empty($this->xml)) {
            return true;
        }

        return false;
    }

    /**
     * Dynamically retrieve the value of an attribute.
     *
     * @param string $key
     * @return mixed
     * @throws Exception
     */
    public function __get($key) {
        if (isset($this->xml->{$key})) {
            return $this->xml->{$key};
        }

        throw new Exception('Attribute "' . $key . '" not found in "' . get_class($this) . '".');
    }

    /**
     * Dynamically set the value of an attribute.
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function __set($key, $value) {
        if (is_null($this->xml)) {
            $this->new[$key] = $value;
        } else {
            $this->xml->$key = $value;
        }
    }

    /**
     * Dynamically check if an attribute is set.
     *
     * @param string $key
     * @return boolean
     */
    public function __isset($key) {
        return isset($this->xml->$key);
    }

    /**
     * Dynamically unset an attribute.
     *
     * @param string $key
     * @return void
     */
    public function __unset($key) {
        unset($this->xml->$key);
    }

    /**
     * Convert the class to its string representation.
     *
     * @return string
     */
    public function __toString() {
        return $this->xml;
    }

}