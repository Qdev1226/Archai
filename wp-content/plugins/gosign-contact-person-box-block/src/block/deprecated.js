import classnames from "classnames";
const { Fragment  } = wp.element;
//import { blockAttributes } from './block';
const blockAttributes = {
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
}
export default [
//version 1
{
	attributes: blockAttributes,
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
			</Fragment>
		);
	}
},


];