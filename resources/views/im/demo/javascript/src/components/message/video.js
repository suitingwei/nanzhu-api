var React = require("react");
var ReactDOM = require('react-dom');
var Avatar = require('../common/avatar');


var VideoMsg = React.createClass({

    componentDidMount: function () {
        var me = this;

        var options = { url: me.props.value };

        options.onFileDownloadComplete = function ( response ) {
            var objectURL = WebIM.utils.parseDownloadResponse.call(Demo.conn, response);

            me.refs.video.src = objectURL;
            log(objectURL);
        };

        options.onFileDownloadError = function () {};

        options.headers = {
            'Accept': 'audio/mp4'
        };

        WebIM.utils.download.call(Demo.conn, options);

    },

    render: function () {
        var icon = this.props.className === 'left' ? 'H' : 'I';

        return (
            <div className={'rel pointer ' + this.props.className}>
                <Avatar src={this.props.src} className={this.props.className + ' small'} />
                <p className={this.props.className}>{this.props.nickname} {this.props.time}</p>
                <div className='webim-msg-value'>
                    <span className='webim-msg-icon font'>{icon}</span>
                    <div className='webim-video-msg'>
                        <video id={this.props.id} ref='video' controls />
                    </div>
                </div>
            </div>
        );
    }
});

module.exports = function ( options, sentByMe ) {
    var props = {
        src: options.avatar || '/chat/demo/images/default.png',
        time: options.time || new Date().toLocaleString(),
        value: options.value || '',
        name: options.name,
        length: options.length || '',
        id: options.id,
        nickname :options.nickname
    };

    var node = document.createElement('div');
    node.className = 'webim-msg-container rel';
    options.wrapper.appendChild(node);

    Demo.api.scrollIntoView(node);

    return ReactDOM.render(
		<VideoMsg {...props} className={sentByMe ? 'right' : 'left'} />,
	    node	
	);
};
