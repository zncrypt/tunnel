<?php

namespace PhpBundle\CryptTunnel\Domain\Libs;

use PhpBundle\Crypt\Domain\Libs\Encoders\EncoderInterface;
use PhpLab\Core\Helpers\StringHelper;
use PhpLab\Core\Legacy\Yii\Helpers\ArrayHelper;
use Symfony\Component\Cache\Adapter\AbstractAdapter;

class Session
{

    private $cache;
    private $id;

    public function __construct(AbstractAdapter $cache)
    {
        $this->cache = $cache;
    }

    public function start($id = null)
    {
        if($id == null || $id == 'undefined') {
            $id = $this->generateId();
            $this->id = $id;
            $this->set('createdAt', time());
        }
        $this->id = $id;
    }

    public function set(string $name, $value) {
        if($this->id == null) {
            throw new \Exception('Session not started!');
        }
        $item = $this->cache->getItem($this->id);
        $data = $item->get();
        ArrayHelper::set($data, $name, $value);
        $item->set($data);
        $this->cache->save($item);
    }

    public function get(string $name, $default = null) {
        if($this->id == null) {
            throw new \Exception('Session not started!');
        }
        $item = $this->cache->getItem($this->id);
        $data = $item->get();
        return ArrayHelper::getValue($data, $name, $default);
    }

    public function remove(string $name) {
        if($this->id == null) {
            throw new \Exception('Session not started!');
        }
        $item = $this->cache->getItem($this->id);
        $data = $item->get();
        ArrayHelper::remove($data, $name);
        $item->set($data);
        $this->cache->save($item);
    }

    public function getSessionId() {
        return $this->id;
    }

    public function hasSession($id): bool {
        //dd($id);
        $item = $this->cache->getItem($id);
        //dd($item);
        return $item->get() == null;
    }

    private function generateId(): string {
        do {
            $id = StringHelper::generateRandomString();
            //dd($id);
            $isHas = $this->hasSession($id);
        } while($isHas == false);
        return $id;
    }

}
