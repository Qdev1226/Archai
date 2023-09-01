/**
 * BLOCK: contact-person-box-block
 *
 * Registering a basic block with Gutenberg.
 * Simple block, renders and saves the same content without any interactivity.
 */

import classnames from "classnames";
import deprecated from './deprecated';
//  Import CSS.
import './style.scss';
import './editor.scss';

const { __ } = wp.i18n; // Import __() from wp.i18n
const { registerBlockType } = wp.blocks; // Import registerBlockType() from wp.blocks
const { Component, Fragment } = wp.element;
const { PanelBody, RangeControl, SelectControl, Toolbar, ToggleControl, TextControl,TextareaControl , Button
 } = wp.components;
const {
	InspectorControls,
	BlockAlignmentToolbar,
	PanelColorSettings,
	BlockControls,
	RichText,
	InnerBlocks,
	AlignmentToolbar
} = wp.editor;
var MediaUpload = wp.editor.MediaUpload;

registerBlockType( 'gosign/contact-person-box-block', {
	// Block name. Block names must be string that contains a namespace prefix. Example: my-plugin/my-custom-block.
	title: __( 'Gosign - Contact person box block' ), // Block title.
	icon: 'groups', // Block icon from Dashicons → https://developer.wordpress.org/resource/dashicons/.
	category: 'common', // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.
	keywords: [
		__( 'contact-person-box-block — Gosign Block' ),
		__( 'profile block' ),
		__( 'guten block' ),
	],
	attributes: {
		title: {
	    	type: 'string',
			selector: 'p',
	    },
	    subtitle: {
			type: 'string',
			selector: 'p',
		},
	    bio: {
			type: 'string',
			selector: 'p',
		},
		image: {
			type: "string"
		},
		imageCaption: {
			type: "string",
			source: "text",
			selector: "figcaption"
		},
		imagePosition: {
			type: "string",
			default: "0"
		},
		horizontalPos: {
			type: "string",
			default: "center"
		},
		verticalPos: {
			type: "string",
			default: "above"
		},
		noWrap: {
			type: "boolean",
			default: false
		},
		align: {
			type: "string",
			default: "full"
		},
		imageColumnWidth: {
			type: "int",
			default: 50
		},emailAddress: {
			type: 'string',
		},
		phone: {
			type: 'string',
		},
		altAttachment: {
			type: 'string',
		},
		 RoundImage: {
	      type: "boolean",
	      default: false
	    },
		displayphone: {
			type: "boolean",
	      	default: false
		},
		displayemail: {
			type: "boolean",
	      	default: false,
		},
	    alignment: {
	        type: 'string',
	        selector: 'a',
	    },
	    borderSize: {
			default: 1,
			type: 'number'
		},
		borderRadius: {
			default: 0,
			type: 'number'
		},
		bordercolor: {
			type: 'string',
			default: '#000000'
		},
	    backgroundColor: {
			type: 'string',
			default: '#ffffff'
		},
		mediaID: {
			type: 'number',
		},
		mediaURL: {
			type: 'string',
			source: 'attribute',
			selector: 'img',
			attribute: 'src',
		},
		facebookURL: {
	        type: 'url'
      	},
      	twitterURL: {
	        type: 'url'
      	},
      	xingURL: {
	        type: 'url'
  		},
      	linkedURL: {
	        type: 'url'
  		},
	},

	getEditWrapperProps(attributes) {
		const { align } = attributes;
		if (
			"left" === align ||
			"right" === align ||
			"wide" === align ||
			"full" === align
		) {
			return { "data-align": align };
		}
	},

	/**
	 * The edit function describes the structure of your block in the context of the editor.
	 * This represents what the editor will render when the block is used.
	 *
	 * The "edit" property must be a valid function.
	 *
	 * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/
	 */
	edit: function(props) {
		const { attributes, setAttributes } = props;
		const {
			align,
			imagePosition,
			horizontalPos,
			verticalPos,
			noWrap,
			title, subtitle, bio, emailAddress,
			imageColumnWidth,
			phone, RoundImage, backgroundColor, bordercolor,
			borderSize,
			borderRadius,
			displayemail, 
			displayphone,
			mediaID, mediaURL,altAttachment,
			facebookURL, twitterURL, xingURL, linkedURL
		} = attributes;

		const $availableGalleryPositions = [
			{
				horizontal: {
					center: [0, 8],
					right: [1, 9, 17, 25],
					left: [2, 10, 18, 26]
				}
			},
			{
				vertical: {
					above: [0, 1, 2],
					intext: [17, 18, 25, 26],
					below: [8, 9, 10]
				}
			}
		];

		const findInArray = (array, number) => {
			const found = array.find(function(no) {
				return no == number;
			});

			return found;
		};

		const widthRangeControl = () => {
			if (verticalPos == "intext") {
				return (
					<RangeControl
						label="In Text Image Width"
						value={imageColumnWidth}
						onChange={width =>
							setAttributes({
								imageColumnWidth: width
							})
						}
						min={0}
						max={100}
					/>
				);
			}
		};
		function onImageSelect(media) {
	    	
		    props.setAttributes({
		        mediaURL: media.url,
            	mediaID: media.id,
            	altAttachment: media.alt,
		    })
		    
		}
		const inspectorControls = (
			<InspectorControls>
				<PanelBody title={__("Text With Image Settings")}>
					<SelectControl
						label="Position and Alignment "
						value={imagePosition}
						options={[
							{ label: "Above Center", value: "0" },
							{ label: "Above Right", value: "1" },
							{ label: "Above Left", value: "2" },
							{ label: "Below Center", value: "8" },
							{ label: "Below Right", value: "9" },
							{ label: "Below Left", value: "10" },
							{ label: "In text Right", value: "17" },
							{ label: "In text Left", value: "18" },
							{ label: "Beside Right", value: "25" },
							{ label: "Beside Left", value: "26" }
						]}
						onChange={imagePosition => {
							$availableGalleryPositions.map((position, index) => {
								if (position.horizontal != undefined) {
									if (
										findInArray(position.horizontal.center, imagePosition) !=
										undefined
									) {
										setAttributes({ horizontalPos: "center" });
									}
									if (
										findInArray(position.horizontal.right, imagePosition) !=
										undefined
									) {
										setAttributes({ horizontalPos: "right" });
									}
									if (
										findInArray(position.horizontal.left, imagePosition) !=
										undefined
									) {
										setAttributes({ horizontalPos: "left" });
									}
								} else {
									if (
										findInArray(position.vertical.above, imagePosition) !=
										undefined
									) {
										setAttributes({ verticalPos: "above" });
									}
									if (
										findInArray(position.vertical.intext, imagePosition) !=
										undefined
									) {
										setAttributes({ verticalPos: "intext" });
									}
									if (
										findInArray(position.vertical.below, imagePosition) !=
										undefined
									) {
										setAttributes({ verticalPos: "below" });
									}
								}
								if (imagePosition == 25 || imagePosition == 26) {
									setAttributes({ noWrap: true });
								} else {
									if (noWrap != false) {
										setAttributes({ noWrap: false });
									}
								}
							});
							setAttributes({ imagePosition: imagePosition });
						}}
					/>
					{widthRangeControl()}
				</PanelBody>
					<PanelBody title={__("Author Block Settings")}> 
			          	<Fragment>
	          
		          			<ToggleControl
							label={__("Enable Round Image")}
							checked={RoundImage}
							onChange={value => setAttributes({ RoundImage: value })}
							/>
							<ToggleControl
							label={__("Hide Phone Icon")}
							checked={displayphone}
							onChange={value => setAttributes({ displayphone: value })}
							/>
							<ToggleControl
							label={__("Hide Email Icon")}
							checked={displayemail}
							onChange={value => setAttributes({ displayemail: value })}
							/>
						   	<PanelBody
								title="Social Media Settings"
								icon="category"
								initialOpen={false}>
								<TextControl
									label={__("Facebook:")}
							       	onChange={function(newfburl) {
										setAttributes({ facebookURL: newfburl });
									  }}
								    value={ facebookURL }
								    placeholder="Enter facebook url"
								    type= "url"
							   	/>
							   	<TextControl
									label={__("Twitter:")}
							       	onChange={function(newturl) {
										setAttributes({ twitterURL: newturl });
									  }}
								    value={ twitterURL }
								    placeholder="Enter twitter url"
								    type= "url"
							   	/>
							   	<TextControl
									label={__("Xing:")}
							       	onChange={function(newxingurl) {
										setAttributes({ xingURL: newxingurl });
									  }}
								    value={ xingURL }
								    placeholder="Enter xing url"
								    type= "url"
							   	/>
							   	<TextControl
									label={__("LinkedIn:")}
							       	onChange={function(newlurl) {
										setAttributes({ linkedURL: newlurl });
									  }}
								    value={ linkedURL }
								    placeholder="Enter linkedin url"
								    type= "url"
							   	/>

							</PanelBody>
							<PanelColorSettings
							  title={__("Background Color Settings")}
							  initialOpen={false}
							  colorSettings={[
								{
								  value: backgroundColor,
								  onChange: value => setAttributes({ backgroundColor: value }),
								  label: __("Select Color")
							  	}
							  ]}
							/>
							<PanelBody
								title="Border Settings"
								icon="category"
								initialOpen={false}>
								<Fragment>
									<PanelColorSettings
										title={ __( 'Border Color ' ) }
										initialOpen={false}
										colorSettings={ [
											{
												value: bordercolor,
												onChange: ( bordercolor ) => setAttributes( { bordercolor: bordercolor }),
												label: __( 'Select Color' ),
											},
										] }
									>
									
									</PanelColorSettings>

									<RangeControl
										label={__('Enter Border Size its in px')}
										value={borderSize}
										min='0'
										max='10'
										step='1'
										onChange={function( borderSize ) {
											setAttributes( { borderSize: borderSize } );
										}}
									/>
									<RangeControl
										label={__('Enter Border Radius its in px')}
										value={borderRadius}
										min='0'
										max='10'
										step='1'
										onChange={function( borderRadius ) {
											setAttributes( { borderRadius: borderRadius } );
										}}
									/>
								</Fragment>
							</PanelBody>
			          	</Fragment>
			        </PanelBody>
				
			</InspectorControls>
		);

		const renderImageBlock = () => {
			if (horizontalPos == "center") {
				const borderradius = RoundImage ? {borderRadius: '50%'} : {borderRadius: '0px'};
				return (
					<div className="ce-gallery">
						<div class="ce-outer">
							<div class="ce-inner">
								<div className="ce-row">
									<div className="ce-column">
											<MediaUpload
						                        onSelect={onImageSelect}
						                        type="image"
						                        value={mediaID}
						                        render={( { open } ) => (
						                            <Button className={mediaID ? 'image-button' : 'button button-large'} onClick={open}>
						                                {!mediaID ? __( 'Upload Image' ) : <img style={borderradius} src={mediaURL} alt={altAttachment} width="200" height="200"/>}
						                            </Button>

						                        )}
						                    />
										
									</div>
								</div>
							</div>
						</div>
					</div>
				);
			} else {
				const borderradius = RoundImage ? {borderRadius: '50%'} : {borderRadius: '0px'};
				return (
					<div
						className="ce-gallery"
						style={{
							width: verticalPos == "intext" ? imageColumnWidth + "%" : "auto"
						}}
					>
						<div className="ce-row">
							<div className="ce-column">
								 <MediaUpload
                        onSelect={onImageSelect}
                        type="image"
                        value={mediaID}
                        render={( { open } ) => (
                            <Button className={mediaID ? 'image-button' : 'button button-large'} onClick={open}>
                                {!mediaID ? __( 'Upload Image' ) : <img style={borderradius} src={mediaURL} alt={altAttachment} width="200" height="200"/>}
                            </Button>

                        )}
                    />
							</div>
						</div>
					</div>
				);
			}
		};

		return (
			<Fragment>
				<div style={{backgroundColor: backgroundColor,border:"solid",borderColor: bordercolor,borderRadius:borderRadius,borderWidth: borderSize}}>
				{inspectorControls}
				<BlockControls>
					<BlockAlignmentToolbar
						value={align}
						onChange={nextAlign => {
							setAttributes({ align: nextAlign });
						}}
						controls={["center", "full"]}
					/>
				</BlockControls>
				{noWrap != true && (
					<header>
						
					</header>
				)}
				<div
					className={classnames(props.className, "ce-textpic", {
						[`ce-${horizontalPos}`]: true,
						[`ce-${verticalPos}`]: true,
						"no-wrap": noWrap
					})}
				>
					{verticalPos != "below" && <Fragment>{renderImageBlock()}</Fragment>}
					<div class="ce-bodytext">
						{noWrap == true && (
							<header>
								
							</header>
						)}
						<div>Title: </div>
						<TextControl
							tagName="p"
							value={title}
							onChange={function(newtitle) {
								setAttributes({ title: newtitle });
							  }}
							
						/>
						<div>Subtitle: </div>
						<TextControl
							tagName="p"
							onChange={function(newsubtitle) {
								setAttributes({ subtitle: newsubtitle });
							  }}
							value={subtitle}

						/>
						<div>Description: </div>
						<TextControl
							tagName="p"
							onChange={function(newbio) {
								setAttributes({ bio: newbio });
							  }}
							value={bio}
							placeholder="Type..."
						/>
					
						<div>Email Address: </div>
						<TextControl
					       	onChange={function(newemailAddress) {
								setAttributes({ emailAddress: newemailAddress });
							  }}
						    value={ emailAddress }
						    placeholder="Enter Email"
						    
						    rel="noopener noreferrer"
					   	/>
					   	<div>Phone: </div>
						<TextControl
					       	onChange={function(newphone) {
								setAttributes({ phone: newphone });
							  }}
						    value={ phone }
						    placeholder="Enter phone number"
						    
						    rel="noopener noreferrer"
					   	/>

					</div>
					{verticalPos == "below" && <Fragment>{renderImageBlock()}</Fragment>}
				</div>
				</div>
			</Fragment>
		);
	},

	/**
	 * The save function defines the way in which the different attributes should be combined
	 * into the final markup, which is then serialized by Gutenberg into post_content.
	 *
	 * The "save" property must be specified and must be a valid function.
	 *
	 * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/
	 */
	save: function(props) {
		const { attributes } = props;
		const {
			align,
			imagePosition,
			horizontalPos,
			verticalPos,
			noWrap,
			title, subtitle, bio, emailAddress,
			imageColumnWidth,
			phone, RoundImage, backgroundColor, bordercolor,
	      borderSize,
		  borderRadius,
		  facebookURL, twitterURL, linkedURL, xingURL
		} = attributes;
		const textStyle = {display: noWrap == true ? "table" : "block"	};
		const borderradius = attributes.RoundImage ? {borderRadius: '50%'} : {borderRadius: '0px'};
		const displayphone = attributes.displayphone ? {display: 'none'} : {display: 'block'};
		const displayemail = attributes.displayemail ? {display: 'none'} : {display: 'block'};
		const renderImageBlockSave = () => {

			if (horizontalPos == "center") {
				return (
					<div className="ce-gallery">
						<div class="ce-outer">
							<div class="ce-inner">
								<div className="ce-row">
									<div className="ce-column">
										<img style={borderradius} src={attributes.mediaURL} alt={attributes.altAttachment} width="200" height="200"/>
									</div>
								</div>
							</div>
						</div>
					</div>
				);
			} else {
				return (
					<div
						className="ce-gallery"
						style={{
							width: verticalPos == "intext" ? imageColumnWidth + "%" : "auto"
						}}
					>
						<div className="ce-row">
							<div className="ce-column">
								<img style={borderradius} src={attributes.mediaURL} alt={attributes.altAttachment} width="200" height="200"/>
							</div>
						</div>
					</div>
				);
			}
		};
		return (
			<Fragment>
				<div className="contact-person-box">
				<div style={{backgroundColor: backgroundColor,border:"solid",borderColor: bordercolor,borderRadius:borderRadius,borderWidth: borderSize}}>
				{noWrap != true && (
					<header>
						
					</header>
				)}
				
				<div
					className={classnames(props.className, "ce-textpic", {
						[`ce-${horizontalPos}`]: true,
						[`ce-${verticalPos}`]: true,
						"no-wrap": noWrap
					})}
				>
					{verticalPos != "below" && (
						<Fragment>{renderImageBlockSave()}</Fragment>
					)}
					<div className="ce-bodytext">
						<h4 className="title">{attributes.title}</h4>
						<p className="subtitle">{attributes.subtitle}</p>
						<p className="description">{attributes.bio}</p>
						<a href={'mailto:'+attributes.emailAddress} class="av-icon-char" target="_blank" rel="noopener noreferrer"><i style={displayemail} class="fa fa-envelope"></i>{attributes.emailAddress}</a>
						<p class="av-icon-char"><i style={displayphone} class="fa fa-phone"></i>{attributes.phone}</p>
						<div class="social-icons">
							{facebookURL && (
								<li>
								<a href={attributes.facebookURL} class="av-icon-char"><i class="fa fa-facebook"></i></a>
								</li>
							)}
							{twitterURL && (
								<li>
								<a href={attributes.twitterURL} class="av-icon-char"><i class="fa fa-twitter "></i></a>
								</li>
							)}
							{xingURL && (
								<li>
								<a href={attributes.xingURL} class="av-icon-char"><i class="fa fa-xing"></i></a>
								</li>
							)}
							{linkedURL && (
								<li>
								<a href={attributes.linkedURL} class="av-icon-char"><i class="fa fa-linkedin"></i></a>
								</li>
							)}
							
						</div>
					</div>
					{verticalPos == "below" && (
						<Fragment>{renderImageBlockSave()}</Fragment>
					)}
				</div>
				</div>
				</div>
			</Fragment>
		);
	},
	deprecated, 
});
