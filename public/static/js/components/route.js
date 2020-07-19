const router = new VueRouter({
  mode: "hash",
  // base:'/app/public/',
  routes: [
    {
      path: "/",
      component: CreateFormPage,
    },
    {
      path: "/:id/:token",
      component: ShowAnswersPage,
    },
    {
      path: "/:id/",
      component: CreateAnswerPage,
    },
  ],
});
