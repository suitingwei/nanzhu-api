var React = require("react");


exports.Input = React.createClass({

    handleChange: function () {
        typeof this.props.change === 'function' && this.props.change(this.refs.input.value);
    },

    componentWillUnmount: function () {
        if ( this.props.keydown ) {
            this.refs.input.removeEventListener('keydown', this.props.keydown);
        }
        this.refs.input = null;
    },

    componentDidMount: function () {
        if ( this.props.keydown ) {
            this.refs.input.addEventListener('keydown', this.props.keydown);
        }
        if ( this.props.defaultFocus ) {
            this.refs.input.focus();
        }
    },

    render: function () {
        var type = this.props.type || 'text';
        var className = this.props.className ? ' ' + this.props.className : '';
        return <input className={'webim-input ' + className}  type={type}  defaultValue={this.props.text} ref='input' placeholder={this.props.placeholder} onChange={this.handleChange} />;
    }
});




exports.Button = React.createClass({

    render: function () {
        var className = this.props.className ? ' ' + this.props.className : '';
        return <button className={'webim-button bg-color' + className}  onClick={this.props.onClick}>{this.props.text}</button>;
    }
});




exports.SmallButton = React.createClass({

    render: function () {
        var className = this.props.status ? ' ' + this.props.status : '';
        return <button className={'webim-button small' + className}  onClick={this.props.click}>{this.props.text}</button>;
    }
});





exports.Radio = React.createClass({

    handleChange: function () {
        this.props.change(this.refs.input.checked);
    },

    render: function () {
        return <input ref='input' type='radio' className='webim-input' defaultValue={this.props.text} onChange={this.handleChange} />;
    }
});





exports.Checkbox = React.createClass({

    getInitialState: function () {
        return {
            checked: false
        };
    },

    handleClick: function () {
        this.toggleChecked();
        this.refs.input.checked = !this.state.checked;
        this.setState({ checked: !this.state.checked });
    },

    toggleChecked: function () {
        if ( this.refs.i.className ) {
            this.refs.i.className = '';
        } else {
            this.refs.i.className = 'checked';
        }
    },

    handleChange: function () {
        typeof this.props.change === 'function' && this.props.change(this.refs.input.value);
    },

    render: function () {
        var className = this.state.checked ? '' : ' hide';

        return (
            <div className='webim-checkbox'>
                <i ref='i' onClick={this.handleClick}>
                    <em ref='rec' className={'font small' + className}>W</em>
                </i>
                <input ref='input' type='checkbox' className='hide' onChange={this.handleChange} />
                <span>{this.props.text}</span>
            </div>
        );
    }
});
