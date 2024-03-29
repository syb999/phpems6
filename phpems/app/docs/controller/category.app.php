<?php
/*
 * Created on 2016-5-19
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class action extends app
{
	public function display()
	{
		$action = $this->ev->url(3);
		if(!method_exists($this,$action))
		$action = "index";
		$this->$action();
		exit;
	}

    private function needmore()
    {
        $page = $this->ev->get('page');
        $catid = intval($this->ev->get('catid'));
        if($catid)$cat = $this->category->getCategoryById($catid);
        $catbread = $this->category->getCategoryPos($catid);
        if($cat)
		{
            if($cat['catuseurl'] && $cat['caturl'])
            header("location:".html_entity_decode($cat['caturl']));
            if($cat['catparent'])$catparent = $this->category->getCategoryById($cat['catparent']);
            $catstring = $this->category->getChildCategoryString($catid);
            if($cat['cattpl'])$template = $cat['cattpl'];
            else $template = 'category_default';
            $this->tpl->assign('cat',$cat);
            $this->tpl->assign('catparent',$catparent);
            $this->tpl->assign('catbread',$catbread);
		}
        $args = array();
        $args[] = array("AND","docneedmore = 1");
        if($catid)
		{
            $args[] = array("AND","find_in_set(doccatid,:doccatid)",'doccatid',$catstring);
		}
        $catchildren = $this->category->getCategoriesByArgs(array(array('AND',"catparent = :catparent",'catparent',$catid),array('AND',"catinmenu = '0'"),array('AND',"catapp = 'docs'")));
        $catbrother = $this->category->getCategoriesByArgs(array(array('AND',"catparent = :catparent",'catparent',intval($cat['catparent'])),array('AND',"catinmenu = '0'"),array('AND',"catapp = 'docs'")));
        $docs = $this->doc->getDocList($args,$page);
        $this->tpl->assign('catbrother',$catbrother);
        $this->tpl->assign('catchildren',$catchildren);
        $this->tpl->assign('categories',$this->category->categories);
        $this->tpl->assign('page',$page);
        $this->tpl->assign('docs',$docs);
        $this->tpl->display('needmore');
    }

	private function index()
	{
		$page = $this->ev->get('page');
		$catid = $this->ev->get('catid');
		$cat = $this->category->getCategoryById($catid);
		if($cat['catuseurl'] && $cat['caturl'])
		header("location:".html_entity_decode($cat['caturl']));
		if($cat['catparent'])$catparent = $this->category->getCategoryById($cat['catparent']);
		$catbread = $this->category->getCategoryPos($catid);
		$catstring = $this->category->getChildCategoryString($catid);
		$catchildren = $this->category->getCategoriesByArgs(array(array('AND',"catparent = :catparent",'catparent',$catid),array('AND',"catinmenu = '0'"),array('AND',"catapp = 'docs'")));
		$docs = $this->doc->getDocList(array(array("AND","find_in_set(doccatid,:doccatid)",'doccatid',$catstring)),$page);
		$catbrother = $this->category->getCategoriesByArgs(array(array('AND',"catparent = :catparent",'catparent',$cat['catparent']),array('AND',"catinmenu = '0'"),array('AND',"catapp = 'docs'")));
		if($cat['cattpl'])$template = $cat['cattpl'];
		else $template = 'category_default';
		$this->tpl->assign('cat',$cat);
		$this->tpl->assign('page',$page);
        $this->tpl->assign('categories',$this->category->categories);
		$this->tpl->assign('catbrother',$catbrother);
		$this->tpl->assign('catchildren',$catchildren);
		$this->tpl->assign('catparent',$catparent);
		$this->tpl->assign('catbread',$catbread);
		$this->tpl->assign('docs',$docs);
		$this->tpl->display($template);
	}
}


?>
