var React = require("react");
var ReactDOM = require('react-dom');
var SignIn = require('./sign/signin');
var SignUp = require('./sign/signup');
var Chat = require('./chat/chat');
var Loading = require('./common/loading');
var UI = require('./common/webim-demo');

var Button = UI.Button;

module.exports = React.createClass({
    getInitialState: function () {

        return {
            signIn: true,
            signUp: false,
            chat: false,
            loadingStatus: false
        };
    },

    update: function (state) {
        this.setState({
            signIn: state.signIn,
            signUp: state.signUp,
            chat: state.chat,
            loadingStatus: state.loadingStatus,
            content: state.content,
            status: state.status
        });
    },

    loading: function (status) {
        this.setState({loadingStatus: status});
    },

    reSignin: function () {
        window.location.reload();
    },

    render: function () {
        var props = {};

        props.rosterChange = this.props.rosterChange;
        props.groupChange = this.props.groupChange;
        props.chatroomChange = this.props.chatroomChange;

        return (
            <div>
                <div className='webim'>
                    <div className={'webim-logo' + (!this.state.signIn && !this.state.signUp ? ' hide' : '')}>
                        <div className="webim-logo-img">
                            <img src='/chat/demo/images/logo.png'/>
                        </div>
                        <Button className="reload-btn" text='重新登录' onClick={this.reSignin}/>
                    </div>
                    <SignIn show={this.state.signIn} {...this.props} update={this.update} loading={this.loading}/>
                    <SignUp show={this.state.signUp} {...this.props} update={this.update} loading={this.loading}/>
                    <Chat show={this.state.chat} {...this.props} update={this.update}
                          loading={this.loading} {...props} />
                    <Loading show={this.state.loadingStatus}/>
                </div>
                <footer className='copyright'>© 2016 南竹通告单+</footer>
            </div>
        );
    }
});
