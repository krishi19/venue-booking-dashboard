export default {
    methods: {
        $is(roleName) {
            let role = localStorage.getItem("role")
              ;
        return role==roleName
        }
    }
};
