<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 那好Model超级父类
 * @author yanrui@91waijiao.com
 */
class NH_Model extends CI_Model
{
    /**
     * @var 判断get_magic_quotes_runtime
     */
    public $boolMagic;

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->boolMagic = get_magic_quotes_runtime();
    }
}