var React = require("react");

module.exports = React.createClass({
    render: function () {
        
        return (
			<div className={'webim-loading' + (this.props.show === 'show' ? '' : ' hide')}>
				<img src='/chat/demo/images/loading.gif' />
			</div>
		);
    }
});
