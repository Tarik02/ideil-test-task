import Dashboard from './pages/Dashboard';
import Places from './pages/Places';
import PlacesShow from './pages/PlacesShow';
import PlacesComments from './pages/PlacesComments';

const routes = [
    { name: 'dashboard', path: '/', component: Dashboard },
    { name: 'places', path: '/places', component: Places, props: route => ({
        page: 'page' in route.query ? Number(route.query.page) : 1,
    }) },
    { name: 'places.create', path: '/places/new', component: PlacesShow },
    { name: 'places.edit', path: '/places/:slug', component: PlacesShow },

    { name: 'places.comments', path: '/places/:slug/comments', component: PlacesComments },
];

export default routes;
