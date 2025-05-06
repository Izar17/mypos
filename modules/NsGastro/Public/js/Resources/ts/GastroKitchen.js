import './scss/gastro.scss';
import { VueMasonryPlugin } from "vue-masonry";
import gastroKitchen from '@/components/gastro-kitchen';
import mitt from 'mitt';
nsExtraComponents['gastro-kitchen'] = gastroKitchen;
nsHooks.addAction('ns-before-mount', 'gastro-mount', () => {
    const emitter = mitt();
    nsDashboardContent.config.globalProperties.emitter = emitter;
    nsDashboardContent.use(VueMasonryPlugin);
});
//# sourceMappingURL=GastroKitchen.js.map