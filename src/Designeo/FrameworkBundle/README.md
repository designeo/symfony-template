# DesigneoFrameworkBundle

* [Coding Standards](https://wiki.designeo.cz/pravidla/designeo_symfony_coding_standards.html)

## Roles <a name="rolesAnchor"></a>

* Define roles in `app/config/security.yml` + `Designeo\FrameworkBundle\Service\RolesProvider`

## Translations

* Use [KnpLabs/DoctrineBehaviors\translatable](https://github.com/KnpLabs/DoctrineBehaviors#translatable)
* Form with translation: [A2LiX Translation Form](http://a2lix.fr/bundles/translation-form/)

## Controllers

* You have to use Controller as a service: [Symfony documentation](http://symfony.com/doc/current/cookbook/controller/service.html)
* Container is setted in Controller - see [ControllerContainerAwareCompilerPass](#controllerContainerAwareCompilerPassAnchor)
* You can use method like `$this->createForm()`, `$this->generateUrl()`, `$this->redirectToRoute()`, `$this->addFlash()` etc.
* You can not use `$this->get()` - use DI instead!
* Do not use `$this->getDoctrine()` - use Model with DI.
* All Controllers have to extend Designeo\FrameworkBundle\Controller\AbstractController

### Designeo\FrameworkBundle\Controller\AbstractController

* Parent of all controllers in application. Controller enforces using DI.

## DI - ControllerContainerAwareCompilerPass <a name="controllerContainerAwareCompilerPassAnchor"></a>

* Set container to controllers

## Entity

* Listeners are added automatically, all you need is to use Trait in your entity.

### TimestampLoggingSubscriber && Timestamps trait

* Removed, use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable instead

### UserTrackedSubscriber && UserTracked trait

* Add tracking of changes to entity - Who created and edited entity
* Logs only 1 user - only user of last change is logged

### EnumPrimaryKey

* Adds primary key to entity (id), which has to be manually setted by setId($id) method

### PrimaryKey

* Adds autogenerated Id to entity

### Uploadables <a name="uploadableAnchor"></a>

* In order to upload some kind of file to an entity, we use the [Vich Uploader bundle](https://github.com/dustin10/VichUploaderBundle).
* You have to configure mapping in config.yml and in entity. Follow [Step 1](https://github.com/dustin10/VichUploaderBundle/blob/master/Resources/doc/usage.md#step-1-configure-an-upload-mapping) and [Step 2](https://github.com/dustin10/VichUploaderBundle/blob/master/Resources/doc/usage.md#step-2-link-the-upload-mapping-to-an-entity) from official documentation. Do not forget to add the `@Vich\Uploadable` annotation to your entity.
* Then you have to create a form type. 

```php
        class FormType extends AbstractType
        {
        
            /**
             * @param FormBuilderInterface $builder
             * @param array                $options
             */
            public function buildForm(FormBuilderInterface $builder, array $options)
            {
                $builder->add('imageFile', 'vich_image');
            }
        }
```

* Vich automatically provides form types `vich_image` and `vich_file`, which handle the upload of an asset, presenting current asset to a user and allows user to delete current asset.
* Do not ever forget to *somehow* trigger a change of mapped entity attributes when uploading new image. Provided Designeo\FrameworkBundle\Entity\Trait\Photo handles this situation by calling touchModifiedAt method. 
    (This method is implemented for example by `Knp\DoctrineBehaviors\Model\Timestampable\TimestampableMethods`).
* In order to render a link to some asset, use twig macro `vich_uploader_asset` as [documented](https://github.com/dustin10/VichUploaderBundle/blob/master/Resources/doc/generating_urls.md): 

```twig
    <a href="{{ vich_uploader_asset(entity, 'attribute') }}">My asset</a>
```

* There is no need for reinventing some naming mechanism. Vich uploader contains so called [Namers](https://github.com/dustin10/VichUploaderBundle/blob/master/Resources/doc/namers.md#namers) which will take care of it for you. 
* If you need further help with form types, have a look at [image](https://github.com/dustin10/VichUploaderBundle/blob/master/Resources/doc/form/vich_image_type.md) and [file](https://github.com/dustin10/VichUploaderBundle/blob/master/Resources/doc/form/vich_file_type.md) docs please.

### Sluggable <a name="sluggableAnchor"></a>

* Add Slug to entity
* Configuration in entity:
		
		use Doctrine\ORM\Mapping\UniqueConstraint;
		use Designeo\FrameworkBundle\Entity\Traits\Sluggable

		 * @ORM\Table(name="experts",
         *     uniqueConstraints={
         *         @UniqueConstraint(
         *             name="expert_unique_slug",
         *             columns={
         *                 "slug"
         *             }
         *         )
         *      }
         * )
         
         class Expert
         {
             use Sluggable;
		 }

* Configuration in translation entity:
		
		use Doctrine\ORM\Mapping\UniqueConstraint;
		use Designeo\FrameworkBundle\Entity\Traits\Sluggable
		 * @ORM\Table(
         *     name="destination_translations",
         *     uniqueConstraints={
         *         @UniqueConstraint(
         *             name="destination_unique_locale_slug",
         *             columns={
         *                 "slug",
         *                 "locale"
         *             }
         *         )
         *      }
         * )
         class DestinationTranslation
         {
             use Translation;
             use Sluggable;
		 }

* Repository

Need to implement iSlugglableRepository:

		use Designeo\FrameworkBundle\Repository\Interfaces\iSlugglableRepository
		class ExpertRepository extends EntityRepository implements iSlugglableRepository
        {
            /**
             * @param string $slug
             * @param string $locale
             *
             * @return bool
             */
            public function slugIsUsed($slug, $locale)
            {
                $entity = $this->findOneBy(['slug' => $slug]);
                return $entity !== null;
            }
        }

Translations:

			public function slugIsUsed($slug, $locale)
	        {
	            $qb = $this->createQueryBuilder('D');
	            $qb
	              ->select('D, DT')
	              ->leftJoin('D.translations', 'DT')
	              ->where('DT.locale = :locale')
	              ->setParameter('locale', $locale)
	                ->andWhere('DT.slug = :slug')
	              ->setParameter('slug', $slug)
	              ->setMaxResults(1);
	    
	            $entity = $qb->getQuery()->getOneOrNullResult();
	    
	            return $entity !== null;
	        }

* Model

1) Inject SlugService
2) Set slug in persist method

		use Designeo\FrameworkBundle\Service\SlugService
		class ExpertModel extends AbstractLoggerAwareModel
        {
		    /**
		     * @var EntityManagerInterface
		     */
		    private $em;
        
            /**
             * @var ExpertRepository
             */
            private $repository;
        
            /**
             * @var SlugService
             */
            private $slugService;
        
            /**
             * @param EntityManagerInterface $em
             * @param ExpertRepository       $expertRepository
             * @param SlugService            $slugService
             */
            public function __construct(
                EntityManagerInterface $em,
                ExpertRepository $expertRepository,
                SlugService $slugService
            )
            {
                $this->em = $em;
                $this->repository = $expertRepository;
                $this->slugService = $slugService;
            }
        
            /**
             * @param Expert $entity
             * @throws ExpertException
             * @throws \AppBundle\Exception\VimeoException
             */
            public function persist(Expert $entity)
            {
                $slug = $this->slugService->getSlug($entity->getName(), $this->repository);
                $entity->setSlug($slug);
        
                $this->em->persist($entity);
                $this->save($entity);
            }

Translations:

			public function persist(Destination $destination)
            {
                foreach ($destination->getTranslations() as $locale => $translation) {
                    $slug = $this->slugService->getSlug($translation->getName(), $this->repository, $locale);
                    $destination->translate($locale)->setSlug($slug);
                }
                $this->save($destination);
            }

## Exception

### DataAccessException <a name="dataAccessExceptionAnchor"></a>

* Exception thrown when user has no access to item:

		use Designeo\FrameworkBundle\Service\UserSubscriptionEnforcer;
		class MyModel ... {
		    /**
		     * @var UserSubscriptionEnforcer
		     */
		    private $userSubscriptionEnforcer;
		    
		    public function __construct(
                ...
                UserSubscriptionEnforcer $userSubscriptionEnforcer
            )
            {
                ...
                $this->userSubscriptionEnforcer = $userSubscriptionEnforcer;
            }
            
            /**
             * @param int $id
             * @throws \Designeo\FrameworkBundle\Exception\DataAccessException
             */
            public function find($id)
            {
                $this->userSubscriptionEnforcer->denyAccessUnlessGranted();
                return $this->newsRepository->find($id);
            }
        }
        
        //Controller:
        public function someAction($id)
        {
            try {
                $items = $this->myModel->find($id);
            } catch (DataAccessException $e) {
                //Error
            }
			
		

## Form

### DateOptions

* Datepicker for forms:

		use Designeo\FrameworkBundle\Form\Traits\DateOptions;
		class UserType extends AbstractType
        {
            use DateOptions;
            public function buildForm(FormBuilderInterface $builder, array $options)
            {
                $builder->add('subscribedToDate', 'date', $this->getDateOptions());
            }

### EntityQueryBuilderCallbacks

* Factory methods for requently used query builders for entity form fields, eg. Ordered entity:

		$builder->add('bank', null, [
           'query_builder' => $this->getOrderCallback('name', 'desc')
        ]);

## Helper

* Strings - webalize function, you can use SlugifyService too (see [entity > Sluggable](#sluggableAnchor))

## Locale

* LocaleProvider - Adds languages to Twig
* LocaleListener - Redirect user to lang from his preferencies

## Model

### AbstractLoggerAwareModel

* Logger service
* Usage:

		use Designeo\FrameworkBundle\Model\AbstractLoggerAwareModel;
		class MyModel extends AbstractLoggerAwareModel {
			public function throwError() {
				$this->logger->error($e, $variable);

* Config:

		app.model.my_model:
                class: AppBundle\Model\MyModel
                parent: 'app.model.abstract_logger_aware_model'

## ParamConverter

* TranslatableEntityParamConverter - see [Repository > FindByLocaleTrait](#findByLocaleTraitAnchor)

## Repository

* iSlugglableRepository - see [Entity > Sluggable](#sluggableAnchor)
* FindByLocaleTrait - use in your repository (use Designeo\FrameworkBundle\Repository\Traits\FindByLocaleTrait;), then in Controller: <a name="findByLocaleTraitAnchor"></a>

		use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
		class DestinationController extends AbstractWebController
		{
			/**
             * @Route("/{slug}", name="web_destination_detail")
             * @Method({"GET"})
             * @ParamConverter(
             *     "destination",
             *     class="AppBundle:Destination",
             *     options={
             *         "useTranslatable": true,
             *         "repository_method" = "findByLocalizedSlug",
             *     }
             * )
             * @Template("")
             *
             * @param Destination $destination
             *
             * @return array
             */
            public function detailAction(Destination $destination)
		}

FindByLocaleTrait uses Designeo\FrameworkBundle\ParamConverter\TranslatableEntityParamConverter ("useTranslatable": true)

## Service

* Notification\Mailer - simple mailer, see UserCreateMail
* Notification\UserCreateMail - Sends e-mail after user create. Implementation is in UserModel.
* RequestTransformer\JsonRequestTransformer - ??
* RolesProvider - Roles for form, where you choose user's role. See [Roles](#rolesAnchor)
* SlugService - see [Entity > Sluggable](#sluggableAnchor)
* UserSubscriptionEnforcer - see [Exceptions > DataAccessException](#dataAccessExceptionAnchor)

## Subscriber

### FOSRedirecterSubscriber

* Allow to control redirection after user actions like log-in, registration, sign-out, change password etc.
* List of available action is in FOS\UserBundle\FOSUserEvents
* Implement redirect you want and register it in getSubscribedEvents()
* Example for log-out user from web and mobile controller:

		#security.yml
		security:
            firewalls:
         	    main:
                    logout:
                        success_handler: app.fos_redirecter_subscriber
                        
        #FOSRedirecterSubscriber class:
        public function onLogoutSuccess(Request $request)
        {
            $requestUri = $request->getRequestUri();
    
            if (strpos($requestUri, 'mobile') !== false) {
                $url = $this->urlGenerator->generate('mobile_homepage_index', ['loggouted' => true]);
            } else {
                $url = $this->urlGenerator->generate('web_homepage_index');
            }
    
            return new RedirectResponse($url);
        }
        
        #Link in template: href="{{ path('fos_user_security_logout') }}?mobile"

* FOS has no action for password resseting - If you need to controll it, you have to create own controller and extends FOS\UserBundle\Controller\ResettingController:

		use FOS\UserBundle\Controller\ResettingController;
        use Symfony\Component\HttpFoundation\RedirectResponse;
        use Symfony\Component\HttpFoundation\Request;
        
        class FOSResettingController extends ResettingController
        {
        
            public function sendEmailAction(Request $request)
            {
                $return = parent::sendEmailAction($request);
                if (!$return instanceof RedirectResponse) {
                    return $return;
                }
                $pieces = explode('email=', $return->getTargetUrl());
                $email = end($pieces);
        
                return new RedirectResponse($this->generateUrl('mobile_user_resetting_check_email',
                  array('email' => $email)
                ));
            }
        
        }

And use this controller:

		#app/config/routing.yml
		mobile_user_resetting_send_email:
            path: /mobile/reset-send-email
            defaults:
                _controller: AppBundle:Mobile/FOSResetting:sendEmail
        
        mobile_user_resetting_check_email:
            path: /mobile/reset-check-email
            defaults:
                _controller: AppBundle:Mobile/FOSResetting:checkEmail

## Twig

* BoolExtension - Render "Yes / No" from boolen property of entity: {{ data_item.enabled|renderBool }}
* RoleExtension - Check role name, if role do not exists, thro Exception. Usage: {% if is_granted(role('ROLE_IN_PROPER_NAME')) %}
