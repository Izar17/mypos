import './scss/gastro.scss';

import { VueMasonryPlugin } from "vue-masonry";
import gastroKitchen from   '@/components/gastro-kitchen';
import mitt from 'mitt';

declare const nsExtraComponents;
declare const nsDashboardContent;
declare const nsHooks;

nsExtraComponents[ 'gastro-kitchen' ] = gastroKitchen;

nsHooks.addAction( 'ns-before-mount', 'gastro-mount', () => {
    const emitter = mitt();
    nsDashboardContent.config.globalProperties.emitter = emitter;
    nsDashboardContent.use(VueMasonryPlugin);
});