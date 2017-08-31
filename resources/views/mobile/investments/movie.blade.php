<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>客户需求</title>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta content="yes" name="apple-touch-fullscreen">
    <link rel="stylesheet" href="{{ asset('assets/mobile/css/mui.min.css') }}">
</head>
<body>
<div class="crew-list crew-create">
    <form id="movie_form" class="mui-input-group" action="/mobile/movie-companies" method="POST">
        {{ csrf_field() }}
        <div class="mui-input-row">
            <label for="">投资类型</label>
            @if($errors->has('invest_types'))
                <input type="text" readonly value="{{ $errors->first('invest_types') }}">
            @endif
        </div>
        @foreach($investTypes as $type)
            <div class="mui-input-row mui-checkbox mui-left">
                <label>{{ $type }}</label>
                <input name="invest_types[]" value="{{ $type }}" type="checkbox"
                       @if( old('invest_types') && in_array($type,old('invest_types'))) checked @endif>
            </div>
        @endforeach
        <div class="mui-input-row">
            <label for="">项目类型</label>
            @if($errors->has('movie_types'))
                <input type="text" readonly value="{{ $errors->first('movie_types') }}">
            @endif
        </div>
        @foreach($movieTypes as $type)
            <div class="mui-input-row mui-checkbox mui-left">
                <label>{{ $type }}</label>
                <input name="movie_types[]" value="{{ $type }}" type="checkbox"
                       @if( old('movie_types') && in_array($type,old('movie_types'))) checked @endif>
            </div>
        @endforeach

        <div class="mui-input-row">
            <label for="">回报需求</label>
            @if($errors->has('reward_types'))
                <input type="text" readonly value="{{ $errors->first('reward_types') }}">
            @endif
        </div>
        @foreach($rewardTypes as  $type)
            <div class="mui-input-row mui-checkbox mui-left">
                <label>{{ $type }}</label>
                <input name="reward_types[]" value="{{ $type}}" type="checkbox"
                       @if( old('reward_types') && in_array($type,old('reward_types'))) checked @endif>
            </div>
        @endforeach

        <div class="mui-input-row">
            <label for="startDate">拍摄开始</label>
            @if($errors->has('start_date'))
                <input type="text" readonly value="{{ $errors->first('start_date') }}">
            @endif
        </div>
        <div class="mui-input-row">
            <input id="startDate" name="start_date" type="date" placeholder="开始日期" value="{{ old('start_date') }}">
        </div>
        <div class="mui-input-row">
            <label for="endDate">拍摄结束</label>
            @if($errors->has('end_date'))
                <input type="text" readonly value="{{ $errors->first('end_date') }}">
            @endif
        </div>
        <div class="mui-input-row">
            <input id="endDate" name="end_date" type="date" placeholder="结束日期" value="{{ old('end_date') }}">
        </div>

        <div class="mui-input-row">
            <label for="IDe">预算下限(元)</label>
            @if($errors->has('budget_bottom'))
                <input type="text" readonly value="{{ $errors->first('budget_bottom') }}">
            @endif
        </div>
        <div class="mui-input-row">
            <input id="IDe" type="number" name="budget_bottom" min="1" value="{{ old('budget_bottom') }}">
        </div>

        <div class="mui-input-row">
            <label for="IDe">预算上限(元)</label>
            @if($errors->has('budget_top'))
                <input type="text" readonly value="{{ $errors->first('budget_top') }}">
            @endif
        </div>
        <div class="mui-input-row">
            <input id="IDe" type="number" name="budget_top" min="1" value="{{ old('budget_top') }}">
        </div>

        <div class="btn-wrap">
            <button class="mui-btn mui-btn-block mui-btn-success">新建</button>
        </div>
    </form>
</div>
<script src="/assets/mobile/js/ui.min.js"></script>
<script>
    mui.init();
</script>
</body>
</html>