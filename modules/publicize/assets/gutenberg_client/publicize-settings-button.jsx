/**
 * Publicize settings button component.
 *
 * Component which allows user to click to open settings
 * in a new window/tab. If window/tab is closed, then
 * connections will be automatically refreshed.
 *
 * @since  5.9.1
 */

/**
 * External dependencies
 */
import React, { Component } from 'react';

/**
 * Internal dependencies
 */
const { __ } = window.wp.i18n;

class PublicizeSettingsButton extends Component {
	/**
	 * Opens up popup so user can view/modify connections
	 *
	 * @since 5.9.1
	 *
	 * @param {object} event Event instance for onClick.
	 */
	settingsClick = ( event ) => {
		const href = 'options-general.php?page=sharing';
		const { refreshCallback } = this.props;
		event.preventDefault();
		/**
		 * Open a popup window, and
		 * when it is closed, refresh connections
		 */
		const popupWin = window.open( href, '', '' );
		const popupTimer = window.setInterval( () => {
			if ( false !== popupWin.closed ) {
				window.clearInterval( popupTimer );
				refreshCallback();
			}
		}, 500 );
	}

	render() {
		return (
			<div className="jetpack-publicize-add-connection-container">
				<span
					className="jetpack-publicize-add-icon dashicons-plus-alt"
				>
				</span>
				<a
					onClick={ this.settingsClick }
					tabIndex="0"
				>
					{ __( 'Connect another service' ) }
				</a>
			</div>
		);
	}
}

export default PublicizeSettingsButton;

