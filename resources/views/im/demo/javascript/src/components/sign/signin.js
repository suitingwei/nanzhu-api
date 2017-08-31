var React = require("react");
var Notify = require('../common/notify');
var UI = require('../common/webim-demo');

var Input = UI.Input;
var Button = UI.Button;
var Checkbox = UI.Checkbox;



module.exports = React.createClass({

    keyDown: function (e) {
        if (e && e.keyCode === 13) {
            this.signin();
        }
    },

    signin: function () {
        var username = this.refs.name.refs.input.value;
        var coverurl = this.refs.coverurl.refs.input.value;
        var nickname = this.refs.nickname.refs.input.value;
        var auth = this.refs.auth.refs.input.value;
        var type = this.refs.token.refs.input.checked;


        if (!username || !auth) {
            Notify.error(Demo.lan.notEmpty);
            return false;
        }

        var options = {
            apiUrl: this.props.config.apiURL,
            user: username.toLowerCase(),
            accessToken: auth,
            pwd: auth,
            appKey: this.props.config.appkey
        };

        if (!type) {
            delete options.accessToken;
        }


        Demo.user = username;
        Demo.nickname =nickname;
        Demo.coverurl = coverurl;
        this.props.loading('show');
        Demo.conn.open(options);
    },


    signup: function () {
        this.props.update({
            signIn: false,
            signUp: true,
            chat: false
        });
    },

    render: function () {

        return (
            <div className={this.props.show ? 'webim-sign' : 'webim-sign hide'}>
                <h2>{Demo.lan.signIn}</h2>
                <div className="div-phone">
                    <Input className='user-name'      ref='name' type="text"/>
                    <Input className='user-cover-url' ref='coverurl' type="text"/>
                    <Input className="user-nick-name" ref='nickname' type="text"/>
                </div>
                <div className='form-code'>
                    <Input placeholder={Demo.lan.password} ref='auth' type='text' keydown={this.keyDown}/>
                    <div className='mob-code'>
                        <Input text={Demo.lan.signCode} value='' type='button'/>{/*重新获取(59)*/}
                    </div>
                </div>
                <Checkbox text={Demo.lan.tokenSignin} ref='token'/>
                <Button className="login-btn" text={Demo.lan.signIn} onClick={this.signin}/>
                <p>{Demo.lan.noaccount}, <i onClick={this.signup}>{Demo.lan.signupnow}</i></p>
            </div>
        );
    }
});
