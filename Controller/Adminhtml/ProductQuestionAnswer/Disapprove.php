<?php
 /**
 * @category  Bhavin ProductQA
 * @package   Bhavin_ProductQA
 * @copyright Copyright (c) 2019 Bhavin
 * @author    Bhavin
 */
namespace Bhavin\ProductQA\Controller\Adminhtml\ProductQuestionAnswer;

use \Magento\Ui\Component\MassAction\Filter;
use \Bhavin\ProductQA\Model\ProductQuestionAnswerFactory;
use \Magento\Framework\Controller\Result\JsonFactory;
use \Magento\Backend\App\Action\Context;
use Bhavin\ProductQA\Model\Source\Status;
use Magento\Framework\UrlInterface;

class Disapprove extends \Magento\Backend\App\Action
{
	
    /**
     * @var UrlInterface
     */
    private $_urlBuilder;
	/**
     * Access Resource ID
     * 
     */
	const RESOURCE_ID = 'Bhavin_ProductQA::productqnswer_disapprove';
	/**
	* Dis approve url
	*/
	const URL_ANSWER_DISAPPROVE = 'bhavin_productqa/product_questionanswer/approve';
    /**
     * Mass Action Filter
     * 
     * @var \Magento\Ui\Component\MassAction\Filter
     */
    protected $_filter;

    /**
     * Question Answer Factory
     * 
     * @var \Bhavin\ProductQA\Model\QuestionAnswerAnswerFactory
     */
    protected $_productQuestionAnswerFactory;

    /**
     * constructor
     * 
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     * @param Context $context
     */
    public function __construct(
        Filter $filter,
        ProductQuestionAnswerFactory $productQuestionAnswerFactory,
		JsonFactory $jsonFactory,
        Context $context
    )
    {
        $this->_filter            = $filter;
		
        $this->_productQuestionAnswerFactory = $productQuestionAnswerFactory;
		
        $this->_jsonFactory = $jsonFactory;
				
        parent::__construct($context);
    }


	/*
	 * Check permission via ACL resource
	 */
	protected function _isAllowed()
	{
		return $this->_authorization->isAllowed(Self::RESOURCE_ID);
	}
	
    /**
     * execute action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $productQuestionAnswerFactory = $this->_productQuestionAnswerFactory->create();
		
		$id = $this->getRequest()->getParam("id");
		
		$jsonResultFactory = $this->_jsonFactory->create();
		
		$status = 0;
		
		$message = "Error! while  changeing status.";
		
		if($id)
		{
			$productQuestionAnswerFactory->load($id);
			
			$productQuestionAnswerFactory->setStatus(Status::STATUS_PANDING);
			
			$productQuestionAnswerFactory->save();
			
			$status = 1;
			
			$message = "Answer Disapprove successfully.";
		}
		
		$jsonResultFactory = $this->_jsonFactory->create();
		
		 $resultData = [
				'status' => $status,
				'status_text' => __("Approve Now"),
				'prev_status_text' => __("Panding"),
				'message' => $message,
				'url'=> $this->getUrl(static::URL_ANSWER_DISAPPROVE, ['id' => $id ] )
		];

		return $jsonResultFactory->setData($resultData);
    }
}
