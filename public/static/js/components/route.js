const router = new VueRouter({
  mode: "hash",
  // base:'/app/public/',
  routes: [
    {
      path: "/",
      component: CreateFormPage,
    },
    {
      path: "/questionnaires/:id/",
      component: ShowAnswersPage,
    },
    {
      path: "/questionnaires/:id/new",
      component: CreateAnswerPage,
    },
  ],
});
