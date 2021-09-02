<template>
    <v-list dense>
        <div>
<!--            <v-list-item link :to="{name:'dashboard'}" color="primary">-->
<!--                <v-list-item-action>-->
<!--                    <v-icon>mdi-account-cog</v-icon>-->
<!--                </v-list-item-action>-->
<!--                <v-list-item-content>-->
<!--                    <v-list-item-title>Dashboard</v-list-item-title>-->
<!--                </v-list-item-content>-->
<!--            </v-list-item>-->
            <v-list-item link v-if="$is('Admin')" :to="{name:'categories'}" color="primary">
                <v-list-item-action>
                    <v-icon >mdi-menu-open</v-icon>
                </v-list-item-action>
                <v-list-item-content>
                    <v-list-item-title>Categories</v-list-item-title>
                </v-list-item-content>
            </v-list-item>
            <v-list-item link v-if="$is('Admin')" :to="{name:'venue-owners'}" color="primary">
                <v-list-item-action>
                    <v-icon >mdi-account-check-outline</v-icon>
                </v-list-item-action>
                <v-list-item-content>
                    <v-list-item-title>Venue Owners</v-list-item-title>
                </v-list-item-content>
            </v-list-item>
            <v-list-item link v-if="$is('Admin')||$is('Venue Owner')" :to="{name:'venues'}" color="primary">
                <v-list-item-action>
                    <v-icon >mdi-fireplace</v-icon>
                </v-list-item-action>
                <v-list-item-content>
                    <v-list-item-title>Venues</v-list-item-title>
                </v-list-item-content>
            </v-list-item>
            <v-list-item v-if="$is('Admin')||$is('Venue Owner')" link :to="{name:'bookings'}" color="primary">
                <v-list-item-action>
                    <v-icon >mdi-ticket-account</v-icon>
                </v-list-item-action>
                <v-list-item-content>
                    <v-list-item-title>Bookings</v-list-item-title>
                </v-list-item-content>
            </v-list-item>
<!--            <v-list-item link :to="{name:'roles'}" color="primary" v-if="$can('view-roles')">-->
<!--                <v-list-item-action>-->
<!--                    <v-icon>mdi-account-key</v-icon>-->
<!--                </v-list-item-action>-->
<!--                <v-list-item-content>-->
<!--                    <v-list-item-title>{{$t('message.roles_and_permissions')}}</v-list-item-title>-->
<!--                </v-list-item-content>-->
<!--            </v-list-item>-->
<!--            <v-list-item link :to="{name:'activity-logs'}" color="primary" v-if="$can('view-logs')">-->
<!--                <v-list-item-action>-->
<!--                    <v-icon size="16">fa-cogs</v-icon>-->
<!--                </v-list-item-action>-->
<!--                <v-list-item-content>-->
<!--                    <v-list-item-title>Activity Logs</v-list-item-title>-->
<!--                </v-list-item-content>-->
<!--            </v-list-item>-->


<!--            <v-list-item link :to="{name:'tax'}">-->
<!--                <v-list-item-action>-->
<!--                    <v-icon size="16">fa-money-check-alt</v-icon>-->
<!--                </v-list-item-action>-->
<!--                <v-list-item-content>-->
<!--                    <v-list-item-title>{{$t('message.tax')}}</v-list-item-title>-->
<!--                </v-list-item-content>-->
<!--            </v-list-item>-->

<!--            <v-list-item link to="/vendor">-->
<!--                <v-list-item-action>-->
<!--                    <v-icon size="16">fa-store</v-icon>-->
<!--                </v-list-item-action>-->
<!--                <v-list-item-content>-->
<!--                    <v-list-item-title>{{$t('message.vendor')}}</v-list-item-title>-->
<!--                </v-list-item-content>-->
<!--            </v-list-item>-->

<!--            <v-list-group no-action>-->
<!--                <template v-slot:activator>-->
<!--                    <v-list-item-action>-->
<!--                        <v-icon size="16">fa-bars</v-icon>-->
<!--                    </v-list-item-action>-->
<!--                    <v-list-item-content>-->
<!--                        <v-list-item-title>{{$t('message.category')}}</v-list-item-title>-->
<!--                    </v-list-item-content>-->
<!--                </template>-->
<!--                <v-list-item link :to="{name:'category'}" :exact="true">-->
<!--                    <v-list-item-content>-->
<!--                        <v-list-item-title>{{$t('message.category')}}</v-list-item-title>-->
<!--                    </v-list-item-content>-->
<!--                </v-list-item>-->
<!--                <v-list-item link :to="{ name: 'category_type' }" :exact="true">-->
<!--                    <v-list-item-content>-->
<!--                        <v-list-item-title>{{$t('message.category_type')}}</v-list-item-title>-->
<!--                    </v-list-item-content>-->
<!--                </v-list-item>-->
<!--            </v-list-group>-->

<!--            <v-list-item link :to="{ name: 'employee' }">-->
<!--                <v-list-item-action>-->
<!--                    <v-icon size="16">fa-users-cog</v-icon>-->
<!--                </v-list-item-action>-->
<!--                <v-list-item-content>-->
<!--                    <v-list-item-title>{{$t('message.employees')}}</v-list-item-title>-->
<!--                </v-list-item-content>-->
<!--            </v-list-item>-->

<!--            <v-list-group no-action>-->
<!--                <template v-slot:activator>-->
<!--                    <v-list-item-action>-->
<!--                        <v-icon size="16">fa-users</v-icon>-->
<!--                    </v-list-item-action>-->
<!--                    <v-list-item-content>-->
<!--                        <v-list-item-title>{{$t('message.customer')}}</v-list-item-title>-->
<!--                    </v-list-item-content>-->
<!--                </template>-->
<!--                <v-list-item link to="/customer" :exact="true">-->
<!--                    <v-list-item-content>-->
<!--                        <v-list-item-title>{{$t('message.customer')}}</v-list-item-title>-->
<!--                    </v-list-item-content>-->
<!--                </v-list-item>-->
<!--                <v-list-item link :to="{ name: 'customer_type' }" :exact="true">-->
<!--                    <v-list-item-content>-->
<!--                        <v-list-item-title>{{$t('message.customer_type')}}</v-list-item-title>-->
<!--                    </v-list-item-content>-->
<!--                </v-list-item>-->
<!--            </v-list-group>-->
<!--            <v-list-group no-action>-->
<!--                <template v-slot:activator>-->
<!--                    <v-list-item-action>-->
<!--                        <v-icon size="16">fa-list-alt</v-icon>-->
<!--                    </v-list-item-action>-->
<!--                    <v-list-item-content>-->
<!--                        <v-list-item-title>{{$t('message.inventory')}}</v-list-item-title>-->
<!--                    </v-list-item-content>-->
<!--                </template>-->

<!--                <v-list-item link to="/products">-->
<!--                    <v-list-item-content>-->
<!--                        <v-list-item-title>{{$t('message.product')}}</v-list-item-title>-->
<!--                    </v-list-item-content>-->
<!--                </v-list-item>-->
<!--                <v-list-item link to="/inventories">-->
<!--                    <v-list-item-content>-->
<!--                        <v-list-item-title>{{$t('message.inventory')}}</v-list-item-title>-->
<!--                    </v-list-item-content>-->
<!--                </v-list-item>-->
<!--                <v-list-item link to="/damage_product">-->
<!--                    <v-list-item-content>-->
<!--                        <v-list-item-title>{{$t('message.damage_product')}}</v-list-item-title>-->
<!--                    </v-list-item-content>-->
<!--                </v-list-item>-->
<!--                <v-list-item link to="/low-product">-->
<!--                    <v-list-item-content>-->
<!--                        <v-list-item-title>{{$t('message.low_product')}}</v-list-item-title>-->
<!--                    </v-list-item-content>-->
<!--                </v-list-item>-->
<!--            </v-list-group>-->
<!--            <v-list-item link to="/locale">-->
<!--                <v-list-item-action>-->
<!--                    <v-icon size="16">fa-flag-usa</v-icon>-->
<!--                </v-list-item-action>-->
<!--                <v-list-item-content>-->
<!--                    <v-list-item-title>{{$t('message.locale')}}</v-list-item-title>-->
<!--                </v-list-item-content>-->
<!--            </v-list-item>-->

<!--            &lt;!&ndash; Report-Section &ndash;&gt;-->

<!--            <v-list-group no-action>-->
<!--                <template v-slot:activator>-->
<!--                    <v-list-item-action>-->
<!--                        <v-icon size="16">widgets</v-icon>-->
<!--                    </v-list-item-action>-->
<!--                    <v-list-item-content>-->
<!--                        <v-list-item-title>{{$t('message.report')}}</v-list-item-title>-->
<!--                    </v-list-item-content>-->
<!--                </template>-->

<!--                <v-list-item link to="/Product-Sale-Report" :exact="true">-->
<!--                    <v-list-item-content>-->
<!--                        <v-list-item-title>{{$t('message.product_sales_report_list')}}</v-list-item-title>-->
<!--                    </v-list-item-content>-->
<!--                </v-list-item>-->
<!--                <v-list-item link to="/customer-transaction-report" :exact="true">-->
<!--                    <v-list-item-content>-->
<!--                        <v-list-item-title>{{$t('message.customer_transaction_report')}}</v-list-item-title>-->
<!--                    </v-list-item-content>-->
<!--                </v-list-item>-->
<!--            </v-list-group>-->
<!--        </div>-->

<!--        <v-list-group no-action>-->
<!--            <template v-slot:activator>-->
<!--                <v-list-item-action>-->
<!--                    <v-icon size="16">fa-shopping-cart</v-icon>-->
<!--                </v-list-item-action>-->
<!--                <v-list-item-content>-->
<!--                    <v-list-item-title>{{$t('message.orders')}}</v-list-item-title>-->
<!--                </v-list-item-content>-->
<!--            </template>-->

<!--            <v-list-item link to="/orders/new" :exact="true">-->
<!--                <v-list-item-content>-->
<!--                    <v-list-item-title>{{$t('message.new_order')}}</v-list-item-title>-->
<!--                </v-list-item-content>-->
<!--            </v-list-item>-->
<!--            <v-list-item link to="/orders" :exact="true">-->
<!--                <v-list-item-content>-->
<!--                    <v-list-item-title>{{$t('message.manage_order')}}</v-list-item-title>-->
<!--                </v-list-item-content>-->
<!--            </v-list-item>-->
<!--        </v-list-group>-->
        </div>
    </v-list>
</template>