<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/bootstrap.min.css">
    <title>Document</title>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <textarea class="form-control" style="width: 100%;" id="text1" cols="150" rows="10"
                      v-model="inputMessage">
            </textarea>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-2">
            <div class="row">
                <div class="col-sm-12">
                    <button class="btn btn-default" v-on:click="save">Save</button>
                </div>
                <div class="col-sm-12" style="margin-top: 2em">
                    <button class="btn btn-default" v-on:click="undo">Undo</button>
                </div>
                <div class="col-sm-12" style="margin-top: 2em">
                    <button class="btn btn-default" v-on:click="redo">Redo</button>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <ul id="historyData">
                <li class="list-group-item">
                    当前undo次数@{{ undoCount }}
                </li>
                <li class="list-group-item">
                    历史数据数组长度@{{ items.length }}
                </li>
            </ul>
        </div>
        <div class="col-sm-6">
            <div class="row">
                <ul id="historyData">
                    <li class="list-group-item" v-for="(item,index) in items">
                        <span class="label label-primary" v-if="index==0">初始数据</span>
                        <span class="label label-primary" v-else>@{{ index }}</span>
                        @{{ item}}
                        <span v-if="index == (items.length-1-undoCount)" class="badge">游标</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
</body>
<script src="/jquery-3.2.0.min.js"></script>
<script src="/vue.js"></script>
<script>
    const example1 = new Vue({
        el: '.container',
        data: {
            undoCount: 0,
            inputMessage: '',
            items: []
        },
        computed: {
            reversedItems: function () {
                return this.items.reverse();
            }
        },
        created(){
            this.inputMessage="第一次保存的xinxi";
            this.save();
        },
        methods: {
            save() {
                this.undoCount = 0;
                this.items.push(this.inputMessage);
            },
            undo() {
                this.undoCount++;
                this.inputMessage = this.items[this.items.length - this.undoCount - 1];
            },
            redo() {
                this.undoCount--;
                this.inputMessage = this.items[this.items.length - this.undoCount - 1];
            }
        }
    });
</script>
</html>