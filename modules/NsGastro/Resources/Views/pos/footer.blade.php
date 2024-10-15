<script>
const GastroSettings    =   {
    icons   :   {
        chair : `{{ asset( 'modules/nsgastro/images/chair.png' ) }}`,
        menu : `{{ asset( 'modules/nsgastro/images/menu.png' ) }}`,
        broke : `{{ asset( 'modules/nsgastro/images/broke.png' ) }}`,
    },
    ns_pos_order_types: <?php echo ns()->option->get( 'ns_pos_order_types' ) ? json_encode( ns()->option->get( 'ns_pos_order_types' ) ) : '[]'; ?>,
    ns_gastro_tables_assignation_enabled: <?php echo ( int ) ns()->option->get( 'ns_gastro_tables_assignation_enabled' ) ? 'true' : 'false'; ?>,
    ns_gastro_kitchen_print_gateway: '<?php echo ns()->option->get( 'ns_gastro_kitchen_print_gateway', 'local_print' ); ?>',
    ns_gastro_areas_enabled: <?php echo ( int ) ns()->option->get( 'ns_gastro_areas_enabled' ) ? 'true' : 'false'; ?>,
    ns_gastro_seats_enabled: <?php echo ( int ) ns()->option->get( 'ns_gastro_seats_enabled' ) ? 'true' : 'false'; ?>,
    ns_gastro_enable_table_sessions: <?php echo ( int ) ns()->option->get( 'ns_gastro_enable_table_sessions' ) ? 'true' : 'false'; ?>,
    ns_gastro_allow_cancelation_print: <?php echo ( int ) ns()->option->get( 'ns_gastro_allow_cancelation_print' ) ? 'true' : 'false'; ?>,
    typeLabels: <?php echo json_encode( config( 'nexopos.orders.types-labels' ) );?>,
    permissions: {
        gastroEditOrder: <?php echo ns()->allowedTo( 'gastro.edit.orders' ) ? 'true' : 'false';?>
    }
}
</script>
@moduleViteAssets( 'Resources/ts/Gastro.ts', 'NsGastro' )
