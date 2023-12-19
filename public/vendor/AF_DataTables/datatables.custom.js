var customColvis = () => {
    const colvisDropdown = $("#colvisDropdown");
    datatable.columns().every(function (index) {
        const column = this;
        const isActive = column.visible(); // Check if the column is visible
        const button = $("<a>", {
            class: "dropdown-item custom-colvis-item",
            href: "#",
            "data-cv-idx": index,
            text: column.header().textContent,
        });
        if (isActive) {
            button.addClass("dt-button-active");
        }
        colvisDropdown.append(button);
    });

    // Initialize Bootstrap 5 dropdown
    $("#customColvisButton").dropdown();
};
