<?php

require_once DATA_PATH.'libs/Smarty.class.php';

	/*
		Smarty���b�v�N���X
	*/
	class SiteView{

		var $_smarty;

		function __construct(){

			$this->_smarty = new Smarty;

	    $this->_smarty->left_delimiter = '<!--{';
	    $this->_smarty->right_delimiter = '}-->';

			// �e���v���[�g�f�B���N�g���ݒ�
			$this->_smarty->template_dir = DATA_PATH."templates";
			// �R���p�C���ς݃e���v���[�g�f�B���N�g���ݒ�
			$this->_smarty->compile_dir = DATA_PATH."templates_c";
			$this->_smarty->caching = false;
		}

		function __destruct(){
		}

		function assign($val1,$val2){
			$this->_smarty->assign($val1,$val2);
		}

		function display($tpl){
			$this->_smarty->display($tpl);
		}
	}

?>