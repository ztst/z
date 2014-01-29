<?
    namespace Znaika\FrontendBundle\Controller;

    use Symfony\Bundle\FrameworkBundle\Controller\Controller;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\Request;

    class DefaultController extends Controller
    {
        public function indexAction()
        {
            return $this->render('ZnaikaFrontendBundle:Default:index.html.twig');
        }

        public function getClassSubjectsAction(Request $request)
        {
            $class             = $request->request->get("class");
            $subjectRepository = $this->get("znaika_frontend.subject_repository");
            $subjects          = $subjectRepository->getByGrade($class);

            $subjectsUrlNames = $this->prepareSubjectsUrlNames($subjects);

            $response = new JsonResponse();
            $response->setData(array(
                'subjectsNames' => $subjectsUrlNames
            ));

            return $response;
        }

        private function prepareSubjectsUrlNames($subjects)
        {
            $subjectsUrlNames = array();
            foreach ( $subjects as $subject )
            {
                array_push( $subjectsUrlNames, $subject->getUrlName() );
            }
            return $subjectsUrlNames;
        }

        public function aboutAction()
        {
            return $this->render('ZnaikaFrontendBundle:Default:about.html.twig');
        }

        public function contactsAction()
        {
            return $this->render('ZnaikaFrontendBundle:Default:contacts.html.twig');
        }

        public function rulesAction()
        {
            return $this->render('ZnaikaFrontendBundle:Default:rules.html.twig');
        }

        public function agreementAction()
        {
            return $this->render('ZnaikaFrontendBundle:Default:agreement.html.twig');
        }

        public function supportAction()
        {
            return $this->render('ZnaikaFrontendBundle:Default:support.html.twig');
        }
    }
