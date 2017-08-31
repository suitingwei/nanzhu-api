<script type="text/javascript" charset="utf-8">
	result();
	function result()
	{
		alert('{{$msg}}');
		if ('{{$msg}}'=="操作失败") {
			window.history.back();
		}
	}
</script>
