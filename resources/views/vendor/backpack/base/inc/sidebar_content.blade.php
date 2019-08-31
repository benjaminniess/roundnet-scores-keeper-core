<!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->
<li><a href="{{ backpack_url('dashboard') }}"><i class="fa fa-dashboard"></i> <span>{{ trans('backpack::base.dashboard') }}</span></a></li>
<li><a href='{{ backpack_url('user') }}'><i class='fa fa-users'></i> <span>Users</span></a></li>
<li class="treeview">
	<a href="#"><i class='fa fa-gamepad'></i> <span>Games</span><i class="fa fa-angle-left pull-right"></i></a>
	<ul class="treeview-menu">
		<li><a href='{{ backpack_url('game') }}'><i class='fa fa-gamepad'></i> <span>Games</span></a></li>
		<li><a href='{{ backpack_url('action-type') }}'><i class='fa fa-tag'></i> <span>Actions types</span></a></li>
	</ul>
</li>
<li class="treeview">
	<a href="#"><i class="fa fa-graduation-cap"></i> <span>Badges</span> <i class="fa fa-angle-left pull-right"></i></a>
	<ul class="treeview-menu">
		<li><a href='{{ backpack_url('badge') }}'><i class="fa fa-graduation-cap"></i> <span>Badges</span></a></li>
		<li><a href='{{ backpack_url('badge-type') }}'><i class='fa fa-tag'></i> <span>Badges Types</span></a></li>
	</ul>
</li>