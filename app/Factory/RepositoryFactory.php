<?php

namespace App\Factory;

use App\Repository\CommentFrontEndRepository;
use App\Repository\CommentBackEndRepository;
use App\Repository\CommentInfoRepository;
use App\Repository\CommentsDefaultAdminRepository;
use App\Repository\CommentsDefaultRepository;
use App\Repository\DiscountsRepository;
use App\Repository\FeedbackRepository;
use App\Repository\PageRepository;
use App\Repository\ProductsRepository;
use App\Repository\ReviewInfoRepository;
use App\Repository\ShopMetaRepository;
use App\Repository\ShopsRepository;
use App\Repository\LogRepository;

class RepositoryFactory
{
	/**
	 * @return ShopsRepository
	 */
	public static function shopsReposity()
	{
		return app(ShopsRepository::class);
	}


	/**
	 * @return ShopMetaRepository
	 */
	public static function shopsMetaReposity()
	{
		return app(ShopMetaRepository::class);
	}


	/**
	 * @return CommentBackEndRepository
	 */
	public static function commentBackEndRepository()
	{
		return app(CommentBackEndRepository::class);
	}

	/**
	 * @return CommentFrontEndRepository
	 */
	public static function commentFrontEndRepository()
	{
		return app(CommentFrontEndRepository::class);
	}

	/**
	 * @return ReviewInfoRepository
	 */
	public static function reviewInfoRepository()
	{
		return app(ReviewInfoRepository::class);
	}


	/**
	 * @return ProductsRepository
	 */
	public static function productsRepository()
	{
		return app(ProductsRepository::class);
	}

	/**
	 * @return CommentInfoRepository
	 */
	public static function commentInfoRepository(){
		return app(CommentInfoRepository::class);
	}

	/**
	 * @return CommentsDefaultRepository
	 */
	public static function commentsDefaultRepository(){
		return app(CommentsDefaultRepository::class);
	}

	/**
	 * @return CommentsDefaultAdminRepository
	 */
	public static function commentsDefaultAdminRepository(){
		return app(CommentsDefaultAdminRepository::class);
	}


	/**
	 * @return PageRepository
	 */
	public static function pageRepository(){
		return app(PageRepository::class);
	}

	/**
	 * @return LogRepository
	 */
	public static function logRepository(){
		return app(LogRepository::class);
	}

	/**
	 * @return FeedbackRepository
	 */
	public static function feedbackRepository(){
		return app(FeedbackRepository::class);
	}

	/**
	 * @return DiscountsRepository
	 */
	public static function discountsRepository(){
		return app(DiscountsRepository::class);
	}
}