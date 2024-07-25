(function() {
	updateNonce();
	async function getRefreshedNonce() {
		try {
			let response = await fetch( cmform.url, {
				method: 'POST',
				headers: new Headers( {
					'Content-Type': 'application/x-www-form-urlencoded',
				} ),
				body: new URLSearchParams({
					action: 'cm_get_refreshed_nonce',
				} ).toString(),
				credentials: 'same-origin',
			} );

			response = await response.json();

			return response?.data?._nonce ?? '';
		} catch( e ) {
			return '';
		}
	}

	async function updateNonce() {
		const updatedNonce = await getRefreshedNonce();
		if ( updatedNonce ) {
			cmform._nonce = updatedNonce;
		}

	}
})();
