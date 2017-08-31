var React = require('react');

var Avatar = require('../common/avatar');
var Cate = require('./cate');
var Operations = require('./operations');


module.exports = React.createClass({

    getInitialState: function () {
        var me = this;

        Demo.selectedCate = 'friends';
        return null;
    },

    shouldComponentUpdate: function ( nextProps, nextState ) {
        return nextProps.cur !== Demo.selectedCate;
    },

    updateFriend: function () {
        Demo.selectedCate = 'friends';
        this.props.update('friend');
    },

    updateGroup: function () {
        Demo.selectedCate = 'groups';
        this.props.update('group');
    },



    render: function () {
        return (
            <div className='webim-leftbar'>
                <Avatar className='webim-profile-avatar small' title={Demo.user} src={Demo.coverurl} />
                <Cate name='friend' update={this.updateFriend} cur={this.props.cur} />
                <Cate name='group' update={this.updateGroup} cur={this.props.cur} />
                <Operations />
            </div>
        );
    }
});
