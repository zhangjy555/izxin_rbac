<?php
namespace Aizxin\Presenters\Admin;
use Aizxin\Repositories\Eloquent\Admin\PermissionRepository;

class MenuPresenter
{
	/**
     * 用户仓库实现.
     *
     * @var UserRepository
     */
    protected $menu;

    /**
     * 创建一个新的属性composer.
     *
     * @param UserRepository $menu
     * @return void
     */
    public function __construct(PermissionRepository $menu)
    {
        $this->menu = $menu;
    }
	// <li class="has-sub active">
	// 	<a href="javascript:;">
	// 	    <b class="caret pull-right"></b>
	// 	    <i class="fa fa-laptop"></i>
	// 	    <span>Dashboard</span>
	//     </a>
	// 	<ul class="sub-menu">
	// 	    <li><a href="index.html">Dashboard v1</a></li>
	// 	    <li class="active"><a href="/admin">Dashboard v2</a></li>
	// 	</ul>
	// </li>
	// <li class="has-sub">
	// 	<a href="javascript:;">
	// 	    <b class="caret pull-right"></b>
	// 	    <i class="fa fa-file-o"></i>
	// 	    <span>文章管理</span>
	// 	</a>
	// 	<ul class="sub-menu">
	// 		<li><a href="email_inbox.html">权限列表</a></li>
	// 	    <li><a href="email_inbox_v2.html">用户列表</a></li>
	// 	    <li><a href="email_compose.html">角色列表</a></li>
	// 	</ul>
	// </li>
	/**
	 *  [sidebarMenus 左侧菜单渲染]
	 *  izxin.com
	 *  @author chouchong
	 *  @DateTime 2016-09-18T17:26:26+0800
	 *  @param    [type]                   $menus [description]
	 *  @return   [type]                          [description]
	 */
	public function sidebarMenus($menus)
	{
		// $currRouteName = \Route::currentRouteName(); // 当前路由别名
		$rule = $this->menu->getMenuId(\Route::currentRouteName());
		$html = '';
		$active = '';
		if ($menus) {
			foreach ($menus as $v) {
				if (auth()->user()->can($v['name'])) {
					if($v['id'] == $rule['parent_id']){
						$active = 'active';
					}
					$html .= '<li class="has-sub '.$active.'">';
					if ($v['child']) {
						$html .= '<a href="javascript:;">';
						    $html .= '<b class="caret pull-right"></b>';
						    $html .= '<i class="'.$v['icon'].'"></i>';
						    $html .= '<span>'.$v['display_name'].'</span>';
					    $html .= '</a>';
					    $html .= $this->getSidebarChildMenu($v['child'],$rule);
					}
					$html .= '</li>';
				}
			}
		}
		// $html .= '<li class="has-sub">'.$rule['name'].'</li>';
		return $html;
	}
	/**
	 *  [getSidebarChildMenu 左侧菜单子菜单渲染]
	 *  izxin.com
	 *  @author chouchong
	 *  @DateTime 2016-09-18T17:26:16+0800
	 *  @param    string                   $childMenu [description]
	 *  @return   [type]                              [description]
	 */
	public function getSidebarChildMenu($childMenu='',$rule)
	{
		$html = '';
		$active = '';
		if ($childMenu) {
			$html .= '<ul class="sub-menu">';
			foreach ($childMenu as $v) {
				if (auth()->user()->can($v['name'])) {
					if($v['id'] == $rule['id']){
						$active = 'active';
					}
					$html .= '<li class="'.$active.'"><a href="'.\URL::route($v['name']).'">'.$v['display_name'].'</a></li>';
				}
			}
			$html .= '</ul>';
		}
		return $html;
	}
}