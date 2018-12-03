const item = document.getElementById('items');

if (item) {
    item.addEventListener('click', (e) => {
	if (e.target.className === 'btn btn-danger delete-item') {
            if (confirm('Are you sure?')) {
		const id = e.target.getAttribute('data-id');

                fetch(`/delete/${id}`, {
                        method: 'DELETE'
                }).then(res => window.location.reload());
            }
	}
    });
}