<?php

namespace Textalk\WebshopClient;

/**
 * A class representing one instance in the API.
 *
 * API-methods can be called directly on this instance.
 *
 * Example:
 *
 * $connection = Connection::getInstance(array('webshop' => 22222));
 * $article = new Instance('Article', 12565609, $connection);
 *
 * $article_data = $article->get(array('name' => 'sv', 'articlegroup' => true));
 */
class ApiInstance
{
    /**
     * Get an instance representing one instance in the API.
     *
     * UID could be null to create a new instance, but this is NOT recommended: using the same handle
     * multiple times would result in several new instances on the backend.  The recommended way
     * would be to first create the instance on the backend, and then use the UID to get an
     * ApiInstance handle:
     *
     * $article_uid = $api->Article->set(null, array('name' => array('en' => 'New article')), 'uid')
     * $article = $api->Article($uid)
     *
     * @param $class      string                            The API class name
     * @param $uid        string|integer                    The API instance UID
     * @param $connection Textalk\WebshopClient\Connection|null The connection, or null for default
     */
    public function __construct($class, $uid, Connection $connection = null)
    {
        $this->class = $class;
        $this->uid   = $uid;
        $this->connection = $connection;
    }

    /**
     * Place all the API-instances methods on this instance magically.
     */
    public function __call($method, $params)
    {
        array_unshift($params, $this->uid);

        return $this->connection->call($this->class . '.' . $method, $params);
    }

  //
  // Protected
  //

    protected $class; ///< The API class name
    protected $uid;   ///< The API instance UID
    protected $connection; ///< The Textalk\WebshopClient\Connection to use
}
