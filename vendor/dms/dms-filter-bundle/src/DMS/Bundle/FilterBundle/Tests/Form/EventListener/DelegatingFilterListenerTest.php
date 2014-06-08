<?php

namespace DMS\Bundle\FilterBundle\Tests\Form\EventListener;

use DMS\Bundle\FilterBundle\Form\EventListener\DelegatingFilterListener;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormBuilder;
use DMS\Bundle\FilterBundle\Service\Filter;
use DMS\Bundle\FilterBundle\Tests\Dummy\AnnotatedClass;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\PropertyAccess\PropertyPath;

class DelegatingFilterListenerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var EventDispatcherInterface | \PHPUnit_Framework_MockObject_MockObject
     */
    private $dispatcher;

    /**
     * @var FormFactoryInterface | \PHPUnit_Framework_MockObject_MockObject
     */
    private $factory;

    /**
     * @var Filter | \PHPUnit_Framework_MockObject_MockObject
     */
    private $delegate;

    /**
     * @var DelegatingFilterListener
     */
    private $listener;

    /**
     * @var string
     */
    private $message;

    /**
     * @var array
     */
    private $params;

    protected function setUp()
    {
        if (!class_exists('Symfony\Component\EventDispatcher\Event')) {
            $this->markTestSkipped('The "EventDispatcher" component is not available');
        }

        $this->dispatcher   = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $this->factory      = $this->getMock('Symfony\Component\Form\FormFactoryInterface');
        $this->delegate     = $this->getMockBuilder('DMS\Bundle\FilterBundle\Service\Filter')
                                   ->disableOriginalConstructor()
                                   ->getMock();
        $this->listener     = new DelegatingFilterListener($this->delegate);

        $this->message = 'Message';
        $this->params = array('foo' => 'bar');
    }

    protected function getBuilder($name = 'name', $propertyPath = null)
    {
        $builder = new FormBuilder($name, '', $this->dispatcher, $this->factory);
        $builder->setAttribute('property_path', new PropertyPath($propertyPath ?: $name));
        $builder->setAttribute('error_mapping', array());
        $builder->setErrorBubbling(false);

        return $builder;
    }

    protected function getForm($name = 'name', $propertyPath = null)
    {
        return $this->getBuilder($name, $propertyPath)->getForm();
    }

    protected function getMockForm()
    {
        return $this->getMock('Symfony\Component\Form\Test\FormInterface');
    }

    public function testFilterIgnoresNonRootWithCascadeOff()
    {
        $form = $this->getMockForm();
        $parentForm = $this->getMockForm();
        $config = $this->getMock('Symfony\Component\Form\FormConfigInterface');

        $form->expects($this->exactly(2))
            ->method('isRoot')
            ->will($this->returnValue(false));

        $form->expects($this->once())
            ->method('getParent')
            ->will($this->returnValue($parentForm));

        $form->expects($this->never())
            ->method('getData');

        $parentForm->expects($this->once())
            ->method('isRoot')
            ->will($this->returnValue(true));

        $parentForm->expects($this->once())
            ->method('getConfig')
            ->will($this->returnValue($config));

        $config->expects($this->once())
            ->method('getOption')
            ->will($this->returnValue(false));

        $this->delegate->expects($this->never())
            ->method('filterEntity');

        $this->listener->onPostSubmit(new FormEvent($form, null));
    }

    public function testFilterFiltersNonRootWithCascadeOn()
    {
        $entity = new AnnotatedClass();
        $form = $this->getMockForm();
        $parentForm = $this->getMockForm();
        $config = $this->getMock('Symfony\Component\Form\FormConfigInterface');

        $form->expects($this->exactly(2))
            ->method('isRoot')
            ->will($this->returnValue(false));

        $form->expects($this->once())
            ->method('getParent')
            ->will($this->returnValue($parentForm));

        $form->expects($this->once())
            ->method('getData')
            ->will($this->returnValue($entity));

        $parentForm->expects($this->once())
            ->method('isRoot')
            ->will($this->returnValue(true));

        $parentForm->expects($this->once())
            ->method('getConfig')
            ->will($this->returnValue($config));

        $config->expects($this->once())
            ->method('getOption')
            ->will($this->returnValue(true));

        $this->delegate->expects($this->once())
            ->method('filterEntity');

        $this->listener->onPostSubmit(new FormEvent($form, null));
    }

    public function testFilterIgnoresNoObject()
    {
        $form = $this->getMockForm();

        $form->expects($this->once())
            ->method('isRoot')
            ->will($this->returnValue(true));

        $form->expects($this->once())
            ->method('getData')
            ->will($this->returnValue(array(1,2,3)));

        $this->delegate->expects($this->never())
            ->method('filterEntity');

        $this->listener->onPostSubmit(new FormEvent($form, null));
    }

    public function testFilterOnPostBind()
    {
        $entity = new AnnotatedClass();
        $form = $this->getMockForm();

        $form->expects($this->once())
            ->method('isRoot')
            ->will($this->returnValue(true));

        $form->expects($this->once())
            ->method('getData')
            ->will($this->returnValue($entity));

        $this->delegate->expects($this->once())
            ->method('filterEntity');

        $this->listener->onPostSubmit(new FormEvent($form, null));
    }

    public function testAssertEventsBinding()
    {
        $bindedEvents = $this->listener->getSubscribedEvents();

        $this->assertArrayHasKey(FormEvents::POST_SUBMIT, $bindedEvents);
    }
}
