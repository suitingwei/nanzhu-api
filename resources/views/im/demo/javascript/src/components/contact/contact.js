var React = require("react");
var Item = require('./contactItem')
var $ = require('jquery');


module.exports = React.createClass({

    getInitialState: function () {
        var me = this;

        return {
            src: '/chat/demo/images/group_user.png'
        };
    },

    update: function (id) {
        this.props.updateNode(id);
    },

    render: function () {
        var friends = [], groups = [];
        var self = this;
        var userArray = [];

        for (var i = 0; i < this.props.friends.length; i++) {
            userArray.push(this.props.friends[i].name.replace('nanzhu_', ''));
        }
        var userIds = userArray.join(',');

        if (userIds != '') {
            $.get('api/im/getUsersInfo?user_ids=' + userIds, function (responseData) {
                var usersInfo = responseData.data.users;

                for (var i = 0; i < usersInfo.length; i++) {
                    var userInfo = usersInfo[i];
                    self.props.friends[i].nickname = userInfo.user_name;
                    self.props.friends[i].coverurl = userInfo.cover_url;
                }
            });
        }

        for (var i = 0; i < this.props.friends.length; i++) {
            friends.push(<Item id={this.props.friends[i].name} cate='friends' key={i}
                               src={this.props.friends[i].coverurl ? this.props.friends[i].coverurl : ''}
                               username={this.props.friends[i].name}
                               nickname={this.props.friends[i].nickname ? this.props.friends[i].nickname : ''}
                               update={this.update} cur={this.props.curNode}/>);
        }

        //加入群聊信息
        var groupArray = [];
        for (var i = 0; i < this.props.groups.length; i++) {
            groupArray.push(this.props.groups[i].roomId);
        }
        var groupIds = groupArray.join(',');
        if (groupIds != '') {
            $.get('api/im/getGroupsInfo?group_ids=' + groupIds, function (responseData) {
                var groupsInfo = responseData.data.groups;

                for (var i = 0; i < groupsInfo.length; i++) {
                    self.props.groups[i].nickname = groupsInfo[i];
                }
            });
        }
        for (var i = 0; i < this.props.groups.length; i++) {
            if(this.props.groups[i].nickname !='') {
                groups.push(<Item id={this.props.groups[i].roomId} cate='groups' key={i}
                                  username={this.props.groups[i].name}
                                  nickname={this.props.groups[i].nickname ? this.props.groups[i].nickname : ''}
                                  update={this.update} cur={this.props.curNode} src={this.state.src}/>);
            }
        }

        return (
            <div className='webim-contact-wrapper'>
                <div className={this.props.cur === 'friend' ? '' : ' hide'}>{friends}</div>
                <div className={this.props.cur === 'group' ? '' : ' hide'}>{groups}</div>
            </div>
        );
    }
});
