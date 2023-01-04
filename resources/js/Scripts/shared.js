export default {
    data() {
        return {
            selected: [],
            selectedItem: false,
            roles: ["coordinator", "admin"],
            perpages: [1, 5, 10, 25, 50, 100],
            status: ["active", "inactive"],
            // quarters: ["Quarter 1", "Quarter 2", "Quarter 3", "Quarter 4"],
            quarters: ["1st", "2nd", "3rd", "4th"],
        };
    },
    methods: {
        selectItem: function (item) {
            this.selectedItem = item;
        },
        unSelectItem: function (item) {
            this.selectedItem = false;
        },

        getRoles: function () {
            let urlParams = new URLSearchParams(window.location.search);
            return urlParams.get("roles");
        },

        getPerPage: function () {
            let urlParams = new URLSearchParams(window.location.search);
            return urlParams.get("perpages");
        },

        getStatus: function () {
            let urlParams = new URLSearchParams(window.location.search);
            return urlParams.get("status");
        },
    },
};
