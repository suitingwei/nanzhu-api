<div class="widget">

	<div class="widget-head">
	  <div class="pull-left">列表</div>
	  <div class="widget-icons pull-right">
		<a href="/mobile/movies/create?user_id={{$user_id}}" >创建</a>
	  </div>
	  <div class="clearfix"></div>
	</div>


	<div class="widget-content">

		<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover">
				<thead>
					<tr>
						<th class="col-md-1">ID</th>
						<th class="col-md-7">Name</th>
						<th class="col-md-1">创始人</th>
						<th class="col-md-2">注册时间</th>
						<th class="col-md-1">操作</th>
					</tr>
				</thead>
				<tbody>
					@foreach($movies as $movie)
					<tr>
						<td>{{$movie->movie_id}}</td>
						<td>{{$movie->movie_name}}</td>
						<td>{{$movie->username}}</td>
						<td>{{$movie->movie_create_at}}</td>
						<td>
							<a href="/mobile/menus?movie_id={{$movie->movie_id}}&user_id={{$movie->user_id}}">菜单</a>
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>

	</div>

</div>

